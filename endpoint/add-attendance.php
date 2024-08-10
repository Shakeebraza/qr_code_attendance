<?php
include("../conn/conn.php");
include("../global.php");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if QR code is set
    if (isset($_POST['qr_code']) && !empty($_POST['qr_code'])) {
        $qrCode = trim($_POST['qr_code']);

        try {
            // Prepare and execute query to fetch student data
            $selectStmt = $conn->prepare("SELECT tbl_student_id, user_id FROM tbl_student WHERE generated_code = :generated_code");
            $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);
            $selectStmt->execute();

            $result = $selectStmt->fetch(PDO::FETCH_ASSOC);

            if ($result !== false) {
                $studentID = $result["tbl_student_id"];
                $userID = $result["user_id"];
                $currentDate = date("Y-m-d");

                // Check if the QR code has already been used today
                $checkStmt = $conn->prepare("SELECT * FROM tbl_attendance WHERE tbl_student_id = :tbl_student_id AND DATE(time_in) = :current_date");
                $checkStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                $checkStmt->bindParam(":current_date", $currentDate, PDO::PARAM_STR);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    echo "<script>
                            alert('You have already checked in today.');
                            window.location.href = '".$urlval."index.php';
                          </script>";
                } else {
                    $timeIn = date("Y-m-d H:i:s");

                    // Prepare and execute query to insert attendance record
                    $insertStmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id, tbl_user_id, time_in, qr_code) VALUES (:tbl_student_id, :user_id, :time_in, :qr_code)");
                    $insertStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                    $insertStmt->bindParam(":user_id", $userID, PDO::PARAM_STR);
                    $insertStmt->bindParam(":time_in", $timeIn, PDO::PARAM_STR);
                    $insertStmt->bindParam(":qr_code", $qrCode, PDO::PARAM_STR);

                    $insertStmt->execute();

                    // Redirect to the index page
                    header("Location: ".$urlval."index.php");
                    exit();
                }
            } else {
                echo "<script>
                        alert('No student found with the provided QR code.');
                        window.location.href = '".$urlval."index.php';
                      </script>";
            }
        } catch (PDOException $e) {
            // Handle and log the error
            error_log("Database error: " . $e->getMessage());
            echo "<script>
                    alert('An error occurred while processing your request. Please try again. Error: " . addslashes($e->getMessage()) . "');
                    window.location.href = '".$urlval."index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('QR code is required.');
                window.location.href = '".$urlval."index.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request method.');
            window.location.href = '".$urlval."index.php';
          </script>";
}
?>
