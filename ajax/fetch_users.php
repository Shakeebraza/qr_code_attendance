<?php

include_once('../conn/conn.php');
include_once('../global.php');

function getUsers($search, $start, $length, $orderColumn, $orderDir) {
    global $conn;
    global $urlval;

    // Base query
    $query = "SELECT id, username, email, type, profile, verified FROM users WHERE type !=1";

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

    // Bind search parameter
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }

    // Bind pagination parameters
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Add action buttons to each row
    foreach ($data as &$row) {
        $row['action'] = '<button class="btn btn-primary btn-sm edit-btn" data-id="' . $row['id'] . '">Edit</button> ' .
                         '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '">Delete</button>';
if(!empty($row['profile'] )){
    
    $row['profile'] = "<img class='tableimage' src='" .$urlval. $row['profile'] . "' alt='Profile Image' style='width: 50px; height: 50px;' />";
}else{
    $row['profile'] = "<img class='tableimage' src='".$urlval."admin/img/user.jpg' alt='Profile Image' style='width: 50px; height: 50px;' />";
    
}

        // Display status text
        $row['verified'] = $row['verified'] == 0 ? "Unverified" : "Verified";
        $row['type'] = $row['type'] == 0 ? "Active" : "Inactive";
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

// Get total records count
$totalRecords = $conn->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Return JSON response
echo json_encode([
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,  // Assuming filtered count is the same as total count for simplicity
    "data" => $data
]);
?>
