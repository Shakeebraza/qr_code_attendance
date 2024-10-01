<?php
include_once('global.php');
$res = $funObject->checksession();
$admincheck = $funObject->isAdmin();
if ($res == 0) {
    header('Location: login.php');
    exit();
}
if ($admincheck == 0) {
    header('Location: empmasterlist.php');
    exit();
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
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
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
    background-blend-mode: multiply, multiply;
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
    width: 90%;
    border-radius: 20px;
    padding: 40px;
    background-color: rgba(255, 255, 255, 0.8);
    box-sizing: border-box;
}

.student-container > div {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    border-radius: 10px;
    padding: 30px;
    height: 100%;
    box-sizing: border-box;
}

.title {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

table.dataTable thead > tr > th.sorting, 
table.dataTable thead > tr > th.sorting_asc, 
table.dataTable thead > tr > th.sorting_desc, 
table.dataTable thead > tr > th.sorting_asc_disabled, 
table.dataTable thead > tr > th.sorting_desc_disabled, 
table.dataTable thead > tr > td.sorting, 
table.dataTable thead > tr > td.sorting_asc, 
table.dataTable thead > tr > td.sorting_desc, 
table.dataTable thead > tr > td.sorting_asc_disabled, 
table.dataTable thead > tr > td.sorting_desc_disabled {
    text-align: center;
}

/* Media Queries for Responsiveness */
@media (max-width: 1200px) {
    .student-container {
        width: 95%;
        padding: 30px;
    }

    .student-container > div {
        padding: 25px;
    }
}

@media (max-width: 992px) {
    .student-container {
        width: 100%;
        padding: 20px;
    }

    .student-container > div {
        padding: 20px;
    }

    .title {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .student-container {
        padding: 15px;
    }

    .student-container > div {
        padding: 15px;
    }

    .title {
        align-items: center;
    }
}

@media (max-width: 576px) {
    .student-container {
        padding: 10px;
    }

    .student-container > div {
        padding: 10px;
    }

    .title {
        align-items: center;
    }
}

    </style>
</head>
<body>
<?php include_once('menu.php'); ?>

    <div class="main">
        <div class="student-container">
            <div class="student-list">
                <div class="title">
                    <h4>List of Employees</h4>
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Add Employee</button>
                </div>
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">username</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            include('./conn/conn.php');

                            // Fetch students data from the database
                            $stmt = $conn->prepare("SELECT * FROM tbl_student 
                                                    JOIN users ON users.id = tbl_student.user_id 
                                                    WHERE users.verified = 1");
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Loop through each student
                            foreach ($result as $row) {
                                $studentID = htmlspecialchars($row["tbl_student_id"], ENT_QUOTES, 'UTF-8');
                                $studentName = htmlspecialchars($row["student_name"], ENT_QUOTES, 'UTF-8');
                                $studentCourse = htmlspecialchars($row["student_name"], ENT_QUOTES, 'UTF-8');
                                $qrCode = htmlspecialchars($row["generated_code"], ENT_QUOTES, 'UTF-8');
                                $qrCodeurl = $row["generated_code_url"];
                        ?>
                            <tr>
                                <th scope="row" id="studentID-<?= $studentID ?>"><?= $studentID ?></th>
                                <td id="studentName-<?= $studentID ?>"><?= $studentName ?></td>
                                <td id="studentCourse-<?= $studentID ?>"><?= $studentCourse ?></td>
                                <td>
                                    <div class="action-button">
                                        <!-- QR Code Modal Trigger -->
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= $studentID ?>">
                                            <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="QR Code Icon" width="16">
                                        </button>

                                        <!-- QR Code Modal -->
                                        <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><?= $studentName ?>'s QR Code</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <!-- QR Code Image -->
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($qrCodeurl) ?>" alt="QR Code for <?= $studentName ?>" width="300">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- Download QR Code Button -->
                                                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($qrCodeurl) ?>" class="btn btn-secondary" download="qrcode.png">Download QR Code</a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Update and Delete Buttons -->
                                        <!-- <button class="btn btn-secondary btn-sm" onclick="updateStudent(<?= $studentID ?>)">&#128393;</button> -->
                                        <!-- <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?= $studentID ?>)">&#10006;</button> -->
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

    <!-- Add Modal -->
    <div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudent">Add Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-student.php" method="POST" onsubmit="return validateForm()">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Number</label>
                            <input type="number" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="button" class="btn btn-secondary form-control qr-generator" onclick="generateQrCode()">Generate QR Code</button>

                        <div class="qr-con text-center" style="display: none;">
                            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                            <p>Take a pic with your QR code.</p>
                            <img class="mb-4" src="" id="qrImg" alt="">
                        </div>
                        <div class="modal-footer modal-close" style="display: none;">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <?php include('script.php'); ?>

    <script>
        $(document).ready(function() {
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
                document.querySelector('.modal-close').style.display = '';
                document.querySelector('.qr-con').style.display = '';
                document.querySelector('.qr-generator').style.display = 'none';
            }
        }
        function validateForm() {
        var numberField = document.getElementById('email').value;
        if (numberField.length < 10) {
            alert('The number must be at least 10 digits long.');
            return false; 
        }

        return true;
    }
    </script>
    
</body>
</html>
