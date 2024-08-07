<?php
include_once('global.php');
$res = $funObject->checksession();
if($res == 0){
    header('Location: login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

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

        .student-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .student-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>
<body>
<?php

include_once('menu.php');
?>


    <div class="main">
        
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>Employee</h4>
               
                </div>
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Course & Section</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                                                            <?php 
                                    include('./conn/conn.php');


                                    $userId = $_SESSION['user_id'];


                                    $stmt = $conn->prepare("SELECT * FROM tbl_student WHERE user_id = :user_id");


                                    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);


                                    $stmt->execute();


                                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


                                    foreach ($result as $row) {
                                        $studentID = $row["tbl_student_id"];
                                        $studentName = $row["student_name"];
                                        $studentCourse = $row["course_section"];
                                        $qrCode = $row["generated_code"];
                                    ?>

                                    <tr>
                                        <th scope="row" id="studentID-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>
                                        </th>
                                        <td id="studentName-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td id="studentCourse-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars($studentCourse, ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                        <td>
                                            <div class="action-button">
                                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="16">
                                                </button>

                                                <!-- QR Modal -->
                                                <div class="modal fade" id="qrCodeModal<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8') ?>'s QR Code</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($qrCode) ?>" alt="" width="300">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                    }
                                    ?>

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
        $(document).ready( function () {
            $('#studentTable').DataTable();
        });

        function updateStudent(id) {
            $("#updateStudentModal").modal("show");

            let updateStudentId = $("#studentID-" + id).text();
            let updateStudentName = $("#studentName-" + id).text();
            let updateStudentCourse = $("#studentCourse-" + id).text();

            $("#updateStudentId").val(updateStudentId);
            $("#updateStudentName").val(updateStudentName);
            $("#updateStudentCourse").val(updateStudentCourse);
        }

        function deleteStudent(id) {
            if (confirm("Do you want to delete this student?")) {
                window.location = "./endpoint/delete-student.php?student=" + id;
            }
        }

        function generateRandomCode(length) {
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            let randomString = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }

            return randomString;
        }

        function generateQrCode() {
            const qrImg = document.getElementById('qrImg');

            let text = generateRandomCode(10);
            $("#generatedCode").val(text);

            if (text === "") {
                alert("Please enter text to generate a QR code.");
                return;
            } else {
                const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;

                qrImg.src = apiUrl;
                document.getElementById('studentName').style.pointerEvents = 'none';
                document.getElementById('studentCourse').style.pointerEvents = 'none';
                document.querySelector('.modal-close').style.display = '';
                document.querySelector('.qr-con').style.display = '';
                document.querySelector('.qr-generator').style.display = 'none';
            }
        }
    </script>
    
</body>
</html>