<?php
include_once('global.php');
$res = $funObject->checksession();
if($res == 0){
    header('Location: login.php');
    exit();
}
$checkworks=$funObject->checkuserworker($_SESSION['user_id']);

@$checkAssignRoom =$funObject->checkAssignRoom($_SESSION['user_id']);

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
        button.btn.btn-success.btn-sm {
            margin-left: 5px;
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
                    <?php
                    // var_dump($checkworks);
                    if($checkworks['record_count'] != 1){
                        echo'
                        <button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal">Genrate QR Code</button>';
                    }
                    ?>
                </div>
                
                <hr>
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th scope="col">Work type</th>
                                <th scope="col">QR Code</th>
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
                                        $datetime = new DateTime($row["date"]);
                                        $date = $datetime->format('Y-m-d');
                                        $time = $datetime->format('H:i:s');
                                        $Work = $row["worktype"];
                                        $qrCode = $row["generated_code"];
                                    ?>

                                            <tr>
                                                <th scope="row" id="studentID-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>
                                                </th>
                                                <td id="studentName-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td id="studentDate-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td id="studentTime-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($time, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td id="studentName-<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($Work, ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td>
                                                    <div class="action-button">
                                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= htmlspecialchars($studentID, ENT_QUOTES, 'UTF-8') ?>">
                                                            <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="20">
                                                            <?php
                                                            if(isset($checkAssignRoom)){
                                                                if ($checkworks['record_count'] != 0 && empty($checkAssignRoom['room']) && $checkAssignRoom['tbl_student_id'] == $studentID) {
                                                                    echo '
                                                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-worktype="'.$Work.'" data-id="'.$studentID.'" data-target="#editStudentModal">
                                                                    <i class="fa fa-edit" width="16"></i>
                                                                    </button>';
                                                                }
                                                            }else{
                                                                
                                                                if($checkworks['tbl_student_id'] == $studentID){
                                                                    echo '
                                                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-worktype="'.$Work.'" data-id="'.$studentID.'" data-target="#editStudentModal">
                                                                        <i class="fa fa-edit" width="16"></i>
                                                                    </button>';
                                                                }
                                                            }
                                                            ?>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addStudentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addStudent" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudent">Today QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/qrgenrate.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $_SESSION['username']?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="work">Work</option>
                            <option value="no_work">No Work</option>
                            <option value="outside_work">Outside Work</option>
                        </select>
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
<!-- Modal edit-->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/qrgenrateedit.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="work">Work</option>
                            <option value="no_work">No Work</option>
                            <option value="outside_work">Outside Work</option>
                        </select>
                    </div>

                    <input type="hidden" id="studentId" name="studentId" value="">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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

        // Ensure QR image and modal elements are displayed correctly
        qrImg.onload = function() {
            document.querySelector('.modal-close').style.display = 'flex';
            document.querySelector('.qr-con').style.display = 'block';
            document.querySelector('.qr-generator').style.display = 'none';
        };
    }
}
$('#editStudentModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var studentId = button.data('id'); // Extract info from data-* attributes
    var worktype = button.data('worktype'); // Extract worktype from data-* attributes

    // Update the modal's content.
    var modal = $(this);
    modal.find('#studentId').val(studentId);
    
    // Set the selected value in the status dropdown
    modal.find('#status').val(worktype);
});
    </script>
    
</body>
</html>