<?php
include_once('../conn/conn.php');
include_once('../global.php');

if (isset($_SESSION['type']) && $_SESSION['type'] != 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        if (isset($_GET['qr_code']) && !empty($_GET['qr_code'])) {
            $qrCode = trim($_GET['qr_code']);

            try {
                // Check if the QR code already exists in the tbl_student table
                $existingQRStmt = $conn->prepare("SELECT tbl_user_id FROM tbl_attendance WHERE qr_code = :generated_code");
                $existingQRStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);
                $existingQRStmt->execute();

                if ($existingQRStmt->rowCount() > 0) {
                    echo "<script>
                            alert('This QR code already exists and cannot be generated again.');
                            window.location.href = '".$urlval."index.php';
                          </script>";
                    exit();
                }

                $selectStmt = $conn->prepare("SELECT tbl_student_id, user_id FROM tbl_student WHERE generated_code = :generated_code");
                $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);
                $selectStmt->execute();

                $result = $selectStmt->fetch(PDO::FETCH_ASSOC);

                if ($result !== false) {
                    $studentID = $result["tbl_student_id"];
                    $userID = $result["user_id"];
                    $currentDate = date("Y-m-d");
                    $currentTime = date("H:i:s");

                    // Define allowed check-in times
                    $startAllowedTime = '17:00:00'; // 5 PM
                    $endAllowedTime = '04:00:00';   // 4 AM

                    // Check if current time is within the allowed range
                    $isWithinAllowedTime = false;
                    
                    if ($currentTime >= $startAllowedTime || $currentTime <= $endAllowedTime) {
                        $isWithinAllowedTime = true;
                    }

                    // if (!$isWithinAllowedTime) {
                    //     echo "<script>
                    //             alert('Check-ins are only allowed between 5 PM and 4 AM.');
                    //             window.location.href = '".$urlval."index.php';
                    //           </script>";
                    //     exit();
                    // }

                    // Check if the QR code has been used today
                    $checkStmt = $conn->prepare("SELECT * FROM tbl_attendance WHERE qr_code = :qr_code AND DATE(time_in) = :current_date");
                    $checkStmt->bindParam(":qr_code", $qrCode, PDO::PARAM_STR);
                    $checkStmt->bindParam(":current_date", $currentDate, PDO::PARAM_STR);
                    $checkStmt->execute();

                    if ($checkStmt->rowCount() > 0) {
                        echo "<script>
                                alert('You have already checked in today.');
                                window.location.href = '".$urlval."index.php';
                              </script>";
                    } else {
                      

                        $insertStmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id, tbl_user_id, time_in, qr_code) VALUES (:tbl_student_id, :user_id, :time_in, :qr_code)");
                        $insertStmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                        $insertStmt->bindParam(":user_id", $userID, PDO::PARAM_STR);
                        $insertStmt->bindParam(":time_in", $todayDateTime, PDO::PARAM_STR);
                        $insertStmt->bindParam(":qr_code", $qrCode, PDO::PARAM_STR);

                        $insertStmt->execute();

                        header("Location: ".$urlval."index.php");
                        exit();
                    }
                } else {
                    echo "<script>
                            alert('No Worker found with the provided QR code.');
                            window.location.href = '".$urlval."index.php';
                          </script>";
                }
            } catch (PDOException $e) {
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
} else {
    echo "<script>
            alert('Please log in as admin.');
            window.location.href = '".$urlval."login.php';
          </script>";
}
?>