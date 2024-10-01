<?php

include_once('../conn/conn.php');
include_once('../global.php');
function getUsers($search, $start, $length, $orderColumn, $orderDir, $user_type, $status) {
    global $conn;
    global $urlval;

       // Base query
    $query = "SELECT id, username, email, type, profile, verified FROM users WHERE id != :user_id";

    // Apply search filter
    if (!empty($search)) {
        $query .= " AND (username LIKE :search OR email LIKE :search)";
    }

    // Apply user type filter
    if (!empty($user_type)) {
        $query .= " AND type = :user_type";
    }
    

    if ($status == 1 || $status == 0) {
        $query .= " AND verified = :status";
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
    if (!empty($user_type)) {
        $stmt->bindValue(':user_type', $user_type, PDO::PARAM_INT);
    }
    if ($status == 1 || $status == 0) {
        $stmt->bindValue(':status', $status, PDO::PARAM_INT);
    }
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($data as &$row) {
  
        $row['action'] = "";

        if($row['type'] == 0){
            $row['action'] .= '
            <a href="'.$urlval.'admin/edituser.php?userid=' . base64_encode(base64_encode($row['id'])). '" class="btn btn-outline-success m-2" data-id="' . $row['id'] . '">Edit</a>';
        }
    
    
        if($_SESSION['type'] == 2){
            if($row['type'] == 1){
                $row['action'] .= '
                <a href="'.$urlval.'admin/edituser.php?userid=' . base64_encode(base64_encode($row['id'])). '" class="btn btn-outline-success m-2" data-id="' . $row['id'] . '">Edit</a>';
            }
        }
    

        if($_SESSION['type'] == 2 || $_SESSION['type'] == 1){
            $row['action'] .= '
            <a href="'.$urlval.'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])) . '" class="btn btn-outline-info m-2" data-id="' . $row['id'] . '">View</a>';
        }
    
     
        if($_SESSION['type'] == 2){
            if($_SESSION['user_id'] != $row['id']){
                $row['action'] .= '
                <button type="button" class="btn btn-outline-primary m-2 delete-btn" data-id="' . $row['id'] . '">Delete</button>';
            }
        }
    
 
        if (!empty($row['profile'])) {
            $row['profile'] = "<img class='tableimage' src='" .$urlval. $row['profile'] . "' alt='Profile Image' style='width: 50px; height: 50px;' />";
        } else {
            $row['profile'] = "<img class='tableimage' src='".$urlval."admin/img/user.jpg' alt='Profile Image' style='width: 50px; height: 50px;' />";
        }
    
  
        $row['verified'] = $row['verified'] == 0 ? "Unverified" : "Verified";
    
       
        if($row['type'] == 1)
        {
            $row['type'] = "Supervisor";
        }
        elseif($row['type'] == 2)
        {
            $row['type'] = "Admin";
        }
        else{
            $row['type'] = "User";
        }
    }
    return $data;
}

$search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
$start = isset($_POST['start']) ? $_POST['start'] : 0;
$length = isset($_POST['length']) ? $_POST['length'] : 10;
$orderColumnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$orderDir = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';

// Capture filter values
$user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
// Map column index to column name
$columns = ['username', 'type', 'verified'];
$orderColumn = $columns[$orderColumnIndex];

// Fetch user data with filters
$data = getUsers($search, $start, $length, $orderColumn, $orderDir, $user_type, $status);

// Get total records count (total and filtered)
$totalRecordsQuery = "SELECT COUNT(*) FROM users WHERE id != :user_id";
$totalRecordsStmt = $conn->prepare($totalRecordsQuery);
$totalRecordsStmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$totalRecordsStmt->execute();
$totalRecords = $totalRecordsStmt->fetchColumn();

$searchCondition = !empty($search) ? " AND (username LIKE :search OR email LIKE :search)" : '';
if (!empty($user_type)) {
    $searchCondition .= " AND type = :user_type";
}
if (!empty($status)) {
    $searchCondition .= " AND verified = :status";
}
$totalFilteredRecordsQuery = "SELECT COUNT(*) FROM users WHERE id != :user_id" . $searchCondition;
$totalFilteredRecordsStmt = $conn->prepare($totalFilteredRecordsQuery);
$totalFilteredRecordsStmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
if (!empty($search)) {
    $totalFilteredRecordsStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
if (!empty($user_type)) {
    $totalFilteredRecordsStmt->bindValue(':user_type', $user_type, PDO::PARAM_INT);
}
if (!empty($status)) {
    $totalFilteredRecordsStmt->bindValue(':status', $status, PDO::PARAM_INT);
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
