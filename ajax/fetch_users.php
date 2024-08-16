<?php

include_once('../conn/conn.php');
include_once('../global.php');
function getUsers($search, $start, $length, $orderColumn, $orderDir) {
    global $conn;
    global $urlval;

    // Base query
    $query = "SELECT id, username, email, type, profile, verified FROM users WHERE id != :user_id";

    // Apply search filter
    if (!empty($search)) {
        $query .= " AND (username LIKE :search OR email LIKE :search)";
    }

    // Apply sorting
    $query .= " ORDER BY $orderColumn $orderDir";

    // Apply pagination
    $query .= " LIMIT :start, :length";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add action buttons to each row
    foreach ($data as &$row) {
        $row['action']= "";

        if($row['type'] == 0){
        $row['action'] .= '
        <a href="'.$urlval.'admin/edituser.php?userid=' . base64_encode(base64_encode($row['id'])). '" class="btn btn-outline-success m-2" data-id="' . $row['id'] . '">Edit</a>';
        }
        if($_SESSION['type'] == 2){
            $row['action'] .= '
            <a href="'.$urlval.'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])) . '" class="btn btn-outline-info m-2" data-id="' . $row['id'] . '">View</a>';
            }
        if($_SESSION['type'] == 2){
            $row['action'] .= '
            <button type="button" class="btn btn-outline-primary m-2 delete-btn" data-id="' . $row['id'] . '">Delete</button>';
        }

        // Profile image handling
        if (!empty($row['profile'])) {
            $row['profile'] = "<img class='tableimage' src='" .$urlval. $row['profile'] . "' alt='Profile Image' style='width: 50px; height: 50px;' />";
        } else {
            $row['profile'] = "<img class='tableimage' src='".$urlval."admin/img/user.jpg' alt='Profile Image' style='width: 50px; height: 50px;' />";
        }

        // Display status text
        $row['verified'] = $row['verified'] == 0 ? "Unverified" : "Verified";
        $row['type'] = $row['type'] == 0 ? "User" : "Supervisor";
    }
    return $data;
}

// Handle DataTables request
$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$start = isset($_POST['start']) ? $_POST['start'] : 0;
$length = isset($_POST['length']) ? $_POST['length'] : 10;
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

// Map column index to column name
$columns = ['id', 'username', 'email', 'type', 'profile', 'verified', 'action'];
$orderColumn = $columns[$orderColumnIndex];

// Fetch user data
$data = getUsers($search, $start, $length, $orderColumn, $orderDir);

// Get total records count (total and filtered)
$totalRecordsQuery = "SELECT COUNT(*) FROM users WHERE id != :user_id";
$totalRecordsStmt = $conn->prepare($totalRecordsQuery);
$totalRecordsStmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

$searchCondition = !empty($search) ? " AND (username LIKE :search OR email LIKE :search)" : '';
$totalFilteredRecordsQuery = "SELECT COUNT(*) FROM users WHERE id != :user_id" . $searchCondition;
$totalFilteredRecordsStmt = $conn->prepare($totalFilteredRecordsQuery);
$totalFilteredRecordsStmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
if (!empty($search)) {
    $totalFilteredRecordsStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
$totalFilteredRecordsStmt->execute();
$totalFilteredRecords = $totalFilteredRecordsStmt->fetchColumn();

// Return JSON response
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalFilteredRecords),
    "data" => $data
]);
?>
