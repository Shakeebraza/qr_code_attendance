<?php
include("../conn/conn.php");
include("../global.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'], $_POST['studentId'])) {
        $username = trim($_POST['username']);
        $work = trim($_POST['status']);
        $studentId = trim($_POST['studentId']); 

        if (!empty($work) && !empty($studentId)) {
            try {
                $userId = $_SESSION['user_id'];
                $todayDate = date('Y-m-d'); // Get today's date

                // Update existing record
                $conn->beginTransaction();

                $stmt = $conn->prepare("
                    UPDATE tbl_student
                    SET student_name = :username, worktype = :worktype, date = NOW()
                    WHERE tbl_student_id  = :studentId AND user_id = :user_id
                ");
                $stmt->bindParam(":studentId", $studentId, PDO::PARAM_INT);
                $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
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