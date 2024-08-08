<?php
include_once('global.php');
$res = $funObject->checksession();

if ($res == 0) {
    header('Location: login.php');
    exit(); 
}
$chksAdmin = $funObject->isAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply,multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .attendance-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .attendance-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
        }

        .attendance-container > div:last-child {
            width: 64%;
            margin-left: auto;
        }

        .scanner-con {
            display: none; 
        }

        .btn-camera {
            margin-top: 10px;
        }

       



    </style>
</head>
<body>
<?php
include_once('menu.php');
?>
    <div class="main">
        <div class="attendance-container row">
            <div class="qr-container col-4">
                <div class="scanner-con">
                    <h5 class="text-center">Scan your QR Code here for your attendance</h5>
                    <video id="interactive" class="viewport" width="100%"></video>
                    <button id="toggle-camera" class="btn btn-secondary btn-camera">Start Camera</button>
                </div>

                <div class="qr-detected-container" style="display: none;">
                    <form action="./endpoint/add-attendance.php" method="POST">
                        <h4 class="text-center">Employe QR Detected!</h4>
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                        <button type="submit" class="btn btn-dark form-control">Submit Attendance</button>
                    </form>
                </div>
            </div>

            <div class="attendance-list">
                <h4>List of Today Present Employe</h4>
                <?php

                    $currentDate = date('Y-m-d');

                    try {
                        // Prepare the SQL query
                        $stmt = $conn->prepare("SELECT 
                                                    tbl_attendance.tbl_attendance_id,
                                                    users.username AS user_name,
                                                    users.email AS user_email,
                                                    users.profile AS user_profile,
                                                    DATE(tbl_attendance.time_in) AS attendance_date,
                                                    TIME(tbl_attendance.time_in) AS time_in
                                                FROM tbl_attendance 
                                                LEFT JOIN users 
                                                ON users.id = tbl_attendance.tbl_user_id 
                                                WHERE DATE(tbl_attendance.time_in) = :currentDate");
                        $stmt->bindParam(':currentDate', $currentDate);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        error_log("Database error: " . $e->getMessage());
                        echo "An error occurred while processing your request.";
                    }
                    ?>

                    <div class="table-container table-responsive">
                        <table class="table text-center table-sm" id="attendanceTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($result as $row) { ?>
                                        <tr>
                                            <th scope="row"><?= htmlspecialchars($row['tbl_attendance_id']) ?></th>
                                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                                            <td><?= htmlspecialchars($row['user_email']) ?></td>
                                            <td>
                                                <?php if (!empty($row['user_profile'])): ?>
                                                    <img src="<?= htmlspecialchars($row['user_profile']) ?>" alt="Profile Picture" class="img-thumbnail rounded-circle" style="max-width: 100px; max-height: 62px;">
                                                <?php else: ?>
                                                    <img src="<?= htmlspecialchars($urlval) ?>admin/img/user.jpg" alt="Profile Picture" class="img-thumbnail rounded-circle" style="max-width: 100px; max-height: 62px;">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                                            <td><?= htmlspecialchars($row['time_in']) ?></td>
                                        </tr>
                                    <?php } ?>


                            </tbody>
                        </table>
                    </div>

            </div>
        </div>
    </div>
<?php
include('script.php');
?>


    <script>
        let scanner;
        let cameraStarted = false;

        function startScanner() {
            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
                $("#detected-qr-code").val(content);
                console.log(content);
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = '';
                document.querySelector(".scanner-con").style.display = 'none';
            });

            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                        cameraStarted = true;
                        document.getElementById('toggle-camera').innerText = 'Stop Camera';
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        function stopScanner() {
            if (scanner) {
                scanner.stop();
                cameraStarted = false;
                document.getElementById('toggle-camera').innerText = 'Start Camera';
            }
        }

        document.getElementById('toggle-camera').addEventListener('click', function() {
            if (cameraStarted) {
                stopScanner();
            } else {
                document.querySelector(".scanner-con").style.display = 'block';
                startScanner();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($chksAdmin != 0) { ?>
                document.querySelector(".scanner-con").style.display = 'block';
                startScanner();
            <?php } ?>
        });

        function deleteAttendance(id) {
            if (confirm("Do you want to remove this attendance?")) {
                window.location = "./endpoint/delete-attendance.php?attendance=" + id;
            }
        }
    </script>
</body>
</html>
