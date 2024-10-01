<?php
include("../conn/conn.php");
include("../global.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['status'], $_POST['studentId'])) {
        $works = trim($_POST['status']);
        $work = '';

        switch($works) {
            case "workNoOuting":
                $work = "Trabajo (sin salida)";
                break;
            case "workWithOuting":
                $work = "Trabajo (con salida)";
                break;
            case "stayNear":
                $work = "Quedarse cerca";
                break;
            default:
                $work = "Sin Trabajo";
                break;
        }

        $studentId = trim($_POST['studentId']);

        if (!empty($work) && !empty($studentId)) {
            try {
                $conn->beginTransaction();

                // Update student record
                $stmt = $conn->prepare("
                    UPDATE tbl_student
                    SET worktype = :worktype, worktypeeng = :worktypeeng
                    WHERE tbl_student_id = :studentId
                ");
                
                $stmt->bindParam(":studentId", $studentId, PDO::PARAM_INT);
                $stmt->bindParam(":worktypeeng", $works, PDO::PARAM_STR);
                $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
                
                $stmt->execute();
                
                $conn->commit();

                // Redirect on success
                header("Location: " . $urlval . "welcome.php");
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "<script>
                        alert('An error occurred: " . addslashes($e->getMessage()) . "');
                        window.location.href = '" . $urlval . "welcome.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Please fill in all fields!');
                    window.location.href = '" . $urlval . "welcome.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Please fill in all fields!');
                window.location.href = '" . $urlval . "welcome.php';
              </script>";
    }
} else {
    header("Location: " . $urlval . "welcome.php");
    exit();
}
?>
