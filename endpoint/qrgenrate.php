<?php
include("../conn/conn.php");
include("../global.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['status'], $_POST['generated_code'])) {
        $username = trim($_POST['username']);
        $works = trim($_POST['status']);
        if($works == "workNoOuting"){
            $work = "Trabajo (sin salida)";
        }elseif($works == "workWithOuting"){
            $work = "Trabajo (con salida)";
        }elseif($works == "stayNear"){
           $work = "Quedarse cerca"; 
        }elseif($works == "stayingclose"){
           $work = "Quedarse cerca (sin salida) ";
          
        }else{
           $work = "Sin Trabajo";  
        }
        $generatedCode = trim($_POST['generated_code']);
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
        
        $generated_code_url = trim($_POST['generated_code_url']);

        if (!empty($username) && !empty($work) && !empty($generatedCode) && !empty($arrivalTime)) {
            try {
                $userId = $_SESSION['user_id'];
                
                $checkStmt = $conn->prepare("
                    SELECT COUNT(*) FROM tbl_student 
                    WHERE user_id = :user_id AND student_name = :username AND DATE(date) = :today_date
                ");
                $checkStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $checkStmt->bindParam(":username", $username, PDO::PARAM_STR);
                $checkStmt->bindParam(":today_date", $todayDateTime, PDO::PARAM_STR);
                $checkStmt->execute();

                $qrCount = $checkStmt->fetchColumn();

                if ($qrCount > 0) {
                    echo "<script>
                            alert('A QR code has already been generated for today!');
                            window.location.href = '{$urlval}empmasterlist.php';
                          </script>";
                } else {
                    $conn->beginTransaction();

                    $stmt = $conn->prepare("
                        INSERT INTO tbl_student (user_id, student_name, generated_code, date, worktype, arrival_time,worktypeeng, generated_code_url) 
                        VALUES (:user_id, :username, :generated_code, :date, :worktype, :arrival_time,:worktypeeng ,:generated_code_url)
                    ");
                    $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
                    $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

                    // Set the current date and time
                    $todayDateTime = date('Y-m-d H:i:s');
                    $stmt->bindParam(":date", $todayDateTime, PDO::PARAM_STR);

                    $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
                    $stmt->bindParam(":arrival_time", $arrivalTime, PDO::PARAM_STR);
                    $stmt->bindParam(":worktypeeng", $works, PDO::PARAM_STR);
                    $stmt->bindParam(":generated_code_url", $generated_code_url, PDO::PARAM_STR);

                    $stmt->execute();
                    $conn->commit();

                    header("Location: " . $urlval . "empmasterlist.php");
                    exit();
                }
            } catch (PDOException $e) {
                $conn->rollBack();
                echo "<script>
                        alert('An error occurred: " . addslashes($e->getMessage()) . "');
                        window.location.href = '{$urlval}empmasterlist.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Please fill in all fields!');
                    window.location.href = '{$urlval}empmasterlist.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Please fill in all fields!');
                window.location.href = '{$urlval}empmasterlist.php';
              </script>";
    }
} else {
    header("Location: " . $urlval . "empmasterlist.php");
    exit();
}


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     if (isset($_POST['username'], $_POST['status'], $_POST['generated_code'])) {
//         $username = trim($_POST['username']);
//         $work = trim($_POST['status']);
//         $generatedCode = trim($_POST['generated_code']);
//         $generated_code_url = trim($_POST['generated_code_url']);

//         if (!empty($username) && !empty($work) && !empty($generatedCode)) {
//             try {
//                 $userId = $_SESSION['user_id'];
                
          
//                 $checkStmt = $conn->prepare("
//                     SELECT COUNT(*) FROM tbl_student 
//                     WHERE user_id = :user_id AND student_name = :username AND DATE(date) = :today_date
//                 ");
              
//                 $checkStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
//                 $checkStmt->bindParam(":username", $username, PDO::PARAM_STR);
//                 $checkStmt->bindParam(":today_date", $todayDateTime, PDO::PARAM_STR);
//                 $checkStmt->execute();

//                 $qrCount = $checkStmt->fetchColumn();

//                 if ($qrCount > 0) {
//                     echo "<script>
//                             alert('A QR code has already been generated for today!');
//                             window.location.href = '{$urlval}empmasterlist.php';
//                           </script>";
//                 } else {
//                     $conn->beginTransaction();

//                     $stmt = $conn->prepare("
//                         INSERT INTO tbl_student (user_id, student_name, generated_code, date, worktype, generated_code_url) 
//                         VALUES (:user_id, :username, :generated_code, :date, :worktype, :generated_code_url)
//                     ");
//                     $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
//                     $stmt->bindParam(":username", $username, PDO::PARAM_STR);
//                     $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

//                     // Set the current date and time
//                     $todayDateTime = date('Y-m-d H:i:s');
//                     $stmt->bindParam(":date", $todayDateTime, PDO::PARAM_STR);

//                     $stmt->bindParam(":worktype", $work, PDO::PARAM_STR);
//                     $stmt->bindParam(":generated_code_url", $generated_code_url, PDO::PARAM_STR);

//                     $stmt->execute();
//                     $conn->commit();

//                     header("Location: " . $urlval . "empmasterlist.php");
//                     exit();
//                 }
//             } catch (PDOException $e) {
//                 $conn->rollBack();
//                 echo "<script>
//                         alert('An error occurred: " . addslashes($e->getMessage()) . "');
//                         window.location.href = '{$urlval}empmasterlist.php';
//                       </script>";
//             }
//         } else {
//             echo "<script>
//                     alert('Please fill in all fields!');
//                     window.location.href = '{$urlval}empmasterlist.php';
//                   </script>";
//         }
//     } else {
//         echo "<script>
//                 alert('Please fill in all fields!');
//                 window.location.href = '{$urlval}empmasterlist.php';
//               </script>";
//     }
// } else {
//     header("Location: " . $urlval . "empmasterlist.php");
//     exit();
// }
?>
