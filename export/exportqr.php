<?php
include_once('../conn/conn.php');
include_once('../global.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $toDate = isset($_POST['todate']) ? trim($_POST['todate']) : ''; // User-provided date
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $worktype = isset($_POST['worktype']) ? trim($_POST['worktype']) : '';

    // If user does not provide a date, use the current date
    $currentDate = date('Y-m-d');
    $finalDate = !empty($toDate) ? $toDate : $currentDate; // Use user date or current date

    try {
        // Set headers for CSV file download
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=export.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Open output stream for writing CSV data
        $output = fopen("php://output", "w");

        // Add CSV header row for the specified fields
        fputcsv($output, ['ID', 'Employee Name', 'Arrival Time', 'Work Type', 'Date']);

        // Prepare your query to fetch only the specified fields
        $query = "
            SELECT 
                tbl_student_id, 
                student_name, 
                arrival_time, 
                worktype, 
                DATE(date) AS date 
            FROM tbl_student 
            WHERE DATE(date) = :finalDate
        ";

        // Add additional filters if provided
        if (!empty($name)) {
            $query .= " AND student_name LIKE :name";
        }
        if (!empty($worktype)) {
            $query .= " AND worktype = :worktype";
        }

        // Prepare and execute the statement
        $stmt = $conn->prepare($query);

        // Bind the final date parameter
        $stmt->bindParam(':finalDate', $finalDate, PDO::PARAM_STR);

        if (!empty($name)) {
            $nameParam = '%' . $name . '%'; // For LIKE clause
            $stmt->bindParam(':name', $nameParam, PDO::PARAM_STR);
        }
        if (!empty($worktype)) {
            $stmt->bindParam(':worktype', $worktype, PDO::PARAM_STR);
        }

        // Execute the query
        $stmt->execute();

        // Check if any records were found
        if ($stmt->rowCount() > 0) {
            // Fetch data and write to CSV
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Only include the specified fields in the CSV
                fputcsv($output, [
                    $row['tbl_student_id'], // Student ID
                    $row['student_name'],   // Student Name
                    $row['arrival_time'],   // Arrival Time
                    $row['worktype'],       // Work Type
                    $row['date']            // Date
                ]);
            }
        } else {
            // Optionally, you can add a message for no results
            fputcsv($output, ['No records found']);
        }

        // Close the output stream
        fclose($output);
        exit();
        
    } catch (PDOException $e) {
        // Handle any errors during the process
        http_response_code(500); // Set a 500 Internal Server Error response code
        echo json_encode(['error' => 'An error occurred while generating the CSV: ' . $e->getMessage()]);
        exit();
    }
}
?>
