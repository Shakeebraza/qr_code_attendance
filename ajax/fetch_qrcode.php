<?php
include_once('../conn/conn.php');
include_once('../global.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        date_default_timezone_set('Your/Timezone');

        $currentTime = new DateTime();

        $date = isset($_POST['date']) && !empty($_POST['date']) ? $_POST['date'] : '';
        $name = isset($_POST['name']) && !empty($_POST['name']) ? $_POST['name'] : '';
        $worktype = isset($_POST['worktype']) && !empty($_POST['worktype']) ? $_POST['worktype'] : '';

        // Set date logic
        if ($date === "" && $currentTime >= new DateTime('00:00') && $currentTime <= new DateTime('11:00')) {
            $date = (new DateTime())->modify('-1 day')->format('Y-m-d');
        } else {
            $date = $date !== "" ? $date : $todayDate;
        }

        // Columns for ordering
        $columns = [
            0 => 'tbl_student_id',
            1 => 'student_name',
            2 => 'worktype',
            3 => 'arrival_time',
        ];

        $columnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; 
        $columnName = isset($columns[$columnIndex]) ? $columns[$columnIndex] : 'tbl_student_id';
        $sortDirection = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; 

        // Base query
        $query = "SELECT * FROM tbl_student WHERE DATE(date) = :date";
        
        // Apply filters for name and worktype
        if (!empty($name)) {
            $query .= " AND student_name LIKE :name";
        }
        
        if (!empty($worktype)) {
            // Linking worktype to worktypeeng field
            $query .= " AND (worktype = :worktype OR worktypeeng = :worktypeeng)";
        }

        $query .= " AND TIME(date) BETWEEN '13:00:00' AND '23:00:00'";
        $query .= " ORDER BY $columnName $sortDirection"; 

        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        if (!empty($name)) {
            $nameParam = '%' . $name . '%'; // To search with LIKE
            $stmt->bindParam(':name', $nameParam, PDO::PARAM_STR);
        }
        if (!empty($worktype)) {
            // Bind both worktype and worktypeeng with the same value
            $stmt->bindParam(':worktype', $worktype, PDO::PARAM_STR);
            $stmt->bindParam(':worktypeeng', $worktype, PDO::PARAM_STR);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            "draw" => intval($_POST['draw']),
            "recordsTotal" => count($results),
            "recordsFiltered" => count($results),
            "data" => $results
        ]);

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
