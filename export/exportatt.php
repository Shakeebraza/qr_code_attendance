<?php
include_once('../global.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_end_clean(); // Clear output buffer

    $fromDate = $_POST['fromdate'] ?? null;
    $toDate = $_POST['todate'] ?? null;
    $name = $_POST['name'] ?? null;

    // SQL query to fetch user and attendance data
    $query = "SELECT users.id, users.username, users.email, attendance.time_in, attendance.room 
              FROM users 
              JOIN tbl_attendance attendance ON users.id = attendance.tbl_user_id 
              WHERE 1=1"; 

    $params = [];

    // Add conditions based on input parameters
    if (!empty($fromDate)) {
        $query .= " AND DATE(attendance.time_in) >= :fromDate";
        $params[':fromDate'] = $fromDate;
    }
    if (!empty($toDate)) {
        $query .= " AND DATE(attendance.time_in) <= :toDate";
        $params[':toDate'] = $toDate;
    }
    if (!empty($name)) {
        $query .= " AND users.username LIKE :name";
        $params[':name'] = "%" . $name . "%";
    }

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($data)) {
        // Set CSV headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export_' . date('Ymd_His') . '.csv"');

        $output = fopen('php://output', 'w');

        // Write the CSV column headers
        fputcsv($output, array('ID', 'Name', 'Date', 'Time', 'Room'));

        // Write the data rows, splitting 'time_in' into 'Date' and 'Time'
        foreach ($data as $row) {
            $timeIn = $row['time_in'];

            // Ensure 'time_in' is properly formatted before splitting
            if ($timeIn && strtotime($timeIn) !== false) {
                $date = date('Y-m-d', strtotime($timeIn));
                $time = date('H:i:s', strtotime($timeIn));
            } else {
                $date = 'Invalid date';
                $time = 'Invalid time';
            }

            // Write the row with the separated Date and Time fields
            fputcsv($output, array($row['id'], $row['username'], $date, $time, $row['room']));
        }

        // Close the output stream
        fclose($output);
    } else {
        echo "No data found.";
    }

    exit;
}
?>
