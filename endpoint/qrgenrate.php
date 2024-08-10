<?php
include("../conn/conn.php");
include("../global.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['status'], $_POST['generated_code'])) {
        $username = trim($_POST['username']);
        $work = trim($_POST['status']);
        $generatedCode = trim($_POST['generated_code']);

        if (!empty($username) && !empty($work) && !empty($generatedCode)) {
            try {
                $userId = $_SESSION['user_id'];
                $todayDate = date('Y-m-d'); // Get today's date

                // Check if a QR code has already been generated today
                $checkStmt = $conn->prepare("
                    SELECT COUNT(*) FROM tbl_student 
                    WHERE user_id = :user_id AND student_name = :username AND DATE(date) = :today_date
                ");
                $checkStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $checkStmt->bindParam(":username", $username, PDO::PARAM_STR);
                $checkStmt->bindParam(":today_date", $todayDate, PDO::PARAM_STR);
                $checkStmt->execute();

                $qrCount = $checkStmt->fetchColumn();

                if ($qrCount > 0) {
                    echo "
                        <script>
                            alert('A QR code has already been generated for today!');
                            window.location.href = '".$urlval."empmasterlist.php';
                        </script>
                    ";
                } else {
                    $conn->beginTransaction();

                    $stmt = $conn->prepare("
                        INSERT INTO tbl_student (user_id, student_name, generated_code, worktype, date) 
                        VALUES (:user_id, :username, :generated_code, :worktype, NOW())
                    ");
                    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                    $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);
                    $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
                    $stmt->execute();

                    $conn->commit();

                    header("Location: " .$urlval."empmasterlist.php");
                    exit();
                }
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
