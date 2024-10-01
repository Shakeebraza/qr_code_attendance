<?php
include("../conn/conn.php");
include("../global.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'], $_POST['studentId'])) {
        $username = trim($_POST['username']);
        $works = trim($_POST['status']);
        if($works == "workNoOuting"){
            $work = "Trabajo (sin salida)";
        }elseif($works == "workWithOuting"){
            $work = "Trabajo (con salida)";
        }elseif($works == "stayNear"){
           $work = "Quedarse cerca";
          
        }
        elseif($works == "stayingclose"){
            $work = "Quedarse cerca (sin salida)";
        }
        else{
           $work = "Sin Trabajo";  
        }

        $studentId = trim($_POST['studentId']);
        $arrivalTimes = trim($_POST['arrival_time']) !== '' ? trim($_POST['arrival_time']) : 'NULL';
                
         if($arrivalTimes == "before8pm"){
            $arrivalTime = "Antes de las 8pm";
        }elseif($arrivalTimes == "8pm"){
            $arrivalTime = "a las 8pm";
        }elseif($arrivalTimes == "9pm"){
           $arrivalTime = "a las 9pm"; 
        }elseif($arrivalTimes == "10pm"){
           $arrivalTime = "a las 10pm";  
        }else{
            $arrivalTime = "NULL";
        }

        if (!empty($work) && !empty($studentId)) {
            try {
                $userId = $_SESSION['user_id'];
                

                // Update existing record
                $conn->beginTransaction();

                $stmt = $conn->prepare("
                    UPDATE tbl_student
                    SET student_name = :username, worktype = :worktype, date = :date ,arrival_time = :arrival_time ,worktypeeng = :worktypeeng
                    WHERE tbl_student_id = :studentId AND user_id = :user_id
                ");
                
                $stmt->bindParam(":studentId", $studentId, PDO::PARAM_INT);
                $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->bindParam(":arrival_time", $arrivalTime, PDO::PARAM_STR);
                $stmt->bindParam(":worktypeeng", $works, PDO::PARAM_STR);
                $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
                $stmt->bindParam(":date", $todayDateTime, PDO::PARAM_STR);
                
                $stmt->execute();
                
                $conn->commit();

                header("Location: " .$urlval."empmasterlist.php");
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "
                    <script>
                        alert('An error occurred: " . addslashes($e->getMessage()) . "');
                        window.location.href = '".$urlval."empmasterlist.php';
                    </script>
                ";
            }
        } else {
            echo "
                <script>
                    alert('Please fill in all fields!');
                    window.location.href = '".$urlval."empmasterlist.php';
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = '".$urlval."empmasterlist.php';
            </script>
        ";
    }
} else {
    header("Location: ".$urlval."empmasterlist.php");
    exit();
}
?>