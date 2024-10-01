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
            /*background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;*/
            background-color: #ebf6f7;
            background-blend-mode: multiply,multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

       
       .student-container{
           width: 80%;
           height: 80%;
       }
       
       

        .student-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 6px 12px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
            background-color: white;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            width:100%;
        }
        @media (max-width: 640px) {
            .title{
                flex-direction: column;
            }
            
        }
         @media (max-width: 640px) {
             
             .student-container{
                 width: 92%;
             }
             
             
             .student-container > div {
                 padding: 5px;
                 box-shadow: rgba(0, 0, 0, 0.24) 0px 6px 12px;
                 
             }
         }
        
         @media (max-width: 640px) {
             .title button{
                    font-weight: 400;
                    font-size: 10px;
                    padding: 5px 10px;
                    margin-top: 5px;
             }
         }
        
       .title button {
        background-color: #54a0ff;
        color: white;
        font-weight: 400;
        font-size: 16px;
        border-radius: 12px;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease;
        
        }

        .title button:hover {
            background-color: #ffff; 
            transform: scale(1.05); 
            color: black;
            border: 1px solid black;
        }

        .title h1{
            font-weight: 800;
            font-size: 35px;
            color: black;
        }
         @media (max-width: 640px) {
              .title h1{
                font-weight: 400;
                font-size: 24px;
                margin-bottom: 5px;
             }
         }
         
         
         #studentTable thead {
             background-color: #000000;
             font-size: 14px;
             font-weight: 300 !important;
             color: #ffff;
             border: none;
             border-top-left-radius: 4px;
             border-top-right-radius: 4px;
         }
         
          #studentTable thead tr th{
              font-weight: 100;
              text-wrap: nowrap;
          }
          #studentTable thead tr{
              border-radius: 4px;
          }
         
          #studentTable tbody{
              font-size: 13px;
              font-weight: 300;
          }
          
           #studentTable tbody tr td{
               text-wrap: nowrap;
           }

        
        button.btn.btn-success.btn-sm {
            margin-left: 5px;
        }
        .action-button{
            text-wrap:nowrap
        }
        .modal-body {
  display: flex;
  justify-content: center;
  align-items: center;
}


    /* Hide the button on mobile screens */
@media (max-width: 767px) {
    .hide-on-mobile {
        display: none; /* Hide the button on screens smaller than 768px */
    }
}

 /* Base styles (applies to all devices unless overridden) */
    .mobile-qr-code-display {
        display: none; /* Hide by default */
    }

    /* Media query for tablets and larger devices */
    @media (min-width: 768px) {
        .mobile-qr-code-display {
            display: none; /* Hide mobile QR code display on tablets and larger */
        }
        .mobile-qr-code-btn {
            display: none; /* Hide mobile QR code button on tablets and larger */
        }
    }

    /* Media query for larger desktop devices */
    @media (min-width: 992px) {
        .mobile-qr-code-display {
            display: none; /* Hide mobile QR code display on desktops */
        }
        .mobile-qr-code-btn {
            display: none; /* Hide mobile QR code button on desktops */
        }
    }
    
    
    
    
    .student-list{
        display: flex;
        flex-direction: column;
        
        align-items: center;
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
                    <h1 class="font-bold" id="employee">Employee</h1>
                    <?php
                 
                
                $startTime = '13:00';
                $endTime = '23:00'; 
                if($_SESSION['verified'] != 0){
                if ($checkworks['record_count'] != 1 && ($todaytime >= $startTime && $todaytime <= $endTime)) {
                    echo '<button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal" id="generateQR">Generate QR Code</button>';
                }else{
                    echo ' <span id="qrtextt">Generar código qr solo de 13:00 a 23:00 horas.</span>'; 
                    //echo '<button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal" id="generateQR">Generate QR Code</button>';
                }
                }else{
                    echo ' <span id="notverfied">El administrador no lo ha verificado. Espere.</span>'; 
                }
                    ?>
                   <!--<button class="btn btn-dark" data-toggle="modal" data-target="#addStudentModal" id="generateQR">Generate QR Code</button>-->
                </div>
                
               
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="studentTable">
                        <thead>
                            <tr>
                                <th scope="col" id="table-id">#</th>
                                <th scope="col" id="table-name"></th>
                                <th scope="col" id="table-date"></th>
                                <th scope="col" id="table-time"></th>
                                <th scope="col" id="table-worktype"></th>
                                <th scope="col" id="table-qrcode"></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    include('./conn/conn.php'); // Include your database connection file
                    
                    session_start(); // Ensure session is started to use session variables
                    
                    // Retrieve user ID from session
                    $userId = $_SESSION['user_id'];
                    
  
                    
                    try {
                        // Prepare SQL statement to select records
                        $stmt = $conn->prepare("SELECT * FROM tbl_student WHERE user_id = :user_id AND DATE(date) = :date");
                    
                        // Bind parameters
                        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                        $stmt->bindParam(':date', $todayDate);
                    
                        // Execute statement
                        $stmt->execute();
                    
                        // Fetch results
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                        // Process each record
                        foreach ($result as $row) {
                            $studentID = htmlspecialchars($row["tbl_student_id"], ENT_QUOTES, 'UTF-8');
                            $studentName = htmlspecialchars($row["student_name"], ENT_QUOTES, 'UTF-8');
                            $datetime = new DateTime($row["date"]);
                            $date = $datetime->format('Y-m-d');
                            $time = $datetime->format('H:i:s');
                            $workType = htmlspecialchars($row["worktype"], ENT_QUOTES, 'UTF-8');
                            $worktypeeng = htmlspecialchars($row["worktypeeng"], ENT_QUOTES, 'UTF-8');
                            $workTypeNoSpaces = str_replace(' ', '', $worktypeeng);
                            $qrCode = htmlspecialchars($row["generated_code"], ENT_QUOTES, 'UTF-8');
                            $qrCodeUrl = htmlspecialchars($row["generated_code_url"], ENT_QUOTES, 'UTF-8');
                            ?>
                    
                            <tr>
                                <th scope="row" id="studentID-<?= $studentID ?>">
                                    <?= $studentID ?>
                                </th>
                                <td id="studentName-<?= $studentID ?>">
                                    <?= $studentName ?>
                                </td>
                                <td id="studentDate-<?= $studentID ?>">
                                    <?= $date ?>
                                </td>
                                <td id="studentTime-<?= $studentID ?>">
                                    <?= $time ?>
                                </td>
                                <td class="<?= $workTypeNoSpaces ?>" id="studentWorkType-<?= $studentID ?>">
                                    <?= $workType ?>
                                </td>
                                <td style="display: flex; align-items: center; justify-content: center;">
                                    <div class="action-button">
                                        
                                    
                    
                                        <?php if (isset($checkAssignRoom)) : ?>
                                            <?php if ($checkworks['record_count'] != 0 && empty($checkAssignRoom['room']) && $checkAssignRoom['tbl_student_id'] == $studentID) : ?>
                                                        <!-- QR Code Modal Trigger Button Mobile Start-->
                                                 
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-worktype="<?= $workType ?>" data-id="<?= $studentID ?>" data-target="#editStudentModal">
                                                    <i class="fa fa-edit" width="16"></i>
                                                </button>
                                                <?php else :?>
                                                    <img src="<?php echo $urlval?>/images/ver.png" alt="QR Code" width="20">
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <?php if ($checkworks['tbl_student_id'] == $studentID) : ?>
                                            <button class="btn btn-primary btn-sm mobile-qr-code-btn" data-qr-code="<?= $qrCodeUrl ?>" data-student-name="<?= $studentName ?>">
                                                    <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="QR Code" width="20">
                                                </button>
                                                <!-- QR Code Modal Trigger Button Mobile End-->
                                                
                                                <!-- QR Code Modal Trigger Button -->
                                                <button class="btn btn-primary btn-sm hide-on-mobile" data-toggle="modal" data-target="#qrCodeModal<?= $studentID ?>">
                                                    <img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="QR Code" width="20">
                                                </button>
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-worktype="<?= $workType ?>" data-id="<?= $studentID ?>" data-target="#editStudentModal">
                                                   <i class="fa fa-edit" width="16" style="color: white;"></i>

                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            
                            
                    
                            <!-- QR Code Modal -->
                            <div class="modal fade" id="qrCodeModal<?= $studentID ?>" tabindex="-1" aria-labelledby="qrCodeModalLabel<?= $studentID ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="qrCodeModalLabel<?= $studentID ?>"><?= $studentName ?>'s QR Code</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($qrCodeUrl) ?>" alt="QR Code" width="300">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" style="width: 100%;" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <?php
                        }
                    } catch (PDOException $e) {
                        // Handle database errors
                        echo "<p>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
                    }
                    
                    // Close connection
                    $conn = null;
                    ?>


                        </tbody>
                    </table>
                </div>
                                            <!-- Hidden div to display QR code (Mobile) Start-->
<div id="mobileQrCodeDisplay" class="mobile-qr-code-display" style="display: none;">
    <div class="mobile-qr-code-header">
        <h5 id="mobileQrCodeTitle"></h5>
        <button type="button" class="close" id="closeMobileQrCodeDisplay" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="mobile-qr-code-body text-center">
        <img id="mobileQrCodeImage" src="" alt="QR Code" width="100%">
    </div>
    <div class="mobile-qr-code-footer">
        <a id="mobileDownloadQrCode" href="" download="QRCode.png" class="btn btn-secondary" style="width: 100%;">Download QR Code</a>
    </div>
</div>
<!-- Hidden div to display QR code (Mobile) End-->
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
                        <label for="username" id="workNameLabel">Work name</label>
                        <input disabled type="text" class="form-control" value="<?php echo $_SESSION['work_name']?>">
                        <input type="hidden" id="username" name="username" value="<?php echo $_SESSION['work_name']?>">
                    </div>
                    <div class="form-group">
                        <label for="status" id="statusLabel">Status</label>
                       
                        <select class="form-control status" id="status" name="status" required>
                            <!--<option value="Trabajo (sin salida)">Trabajo (sin salida)</option>-->
                            <!--<option value="Trabajo (con Salida)">Trabajo (con Salida)</option>-->
                            <!--<option value="Quedarse cerca (sin salida) ">Quedarse cerca (sin salida) </option>-->
                            <!--<option value="Quedarse cerca (con salida)">Quedarse cerca (con salida)</option>-->
                            <!--<option value="Sin trabajo">Sin trabajo</option>-->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Arrival" id="arrivalLabel">Arrival time</label>
                        <select class="form-control arrival" id="arrival" name="arrival_time" required>
                            <option value="Antes de las 8pm">Before 8pm</option>
                            <option value="a las 8pm">At 8pm</option>
                            <option value="a las 9pm">At 9pm</option>
                            <option value="a las 10pm">At 10pm</option>
                        </select>
                    </div>
                    <div class="form-group" id="nullArrivalGroup" style="display: none;">
                        <input type="text" class="form-control" id="nullArrival" name="" value="Null" readonly>
                    </div>
                    <button type="button" class="btn btn-primary form-control qr-generator" id="generateQRButton" onclick="generateQrCode()">Generate QR Code</button>
                    <div class="qr-con text-center" style="display: none;">
                        <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                        <input type="hidden" class="form-control" id="generatedCodeURL" name="generated_code_url">
                        <p id="takePicLabel">Take a pic with your QR code.</p>
                        <img class="mb-4" src="" id="qrImg" alt="" style="filter: blur(3px);">
                    </div>
                    <div class="modal-footer modal-close" style="display: none;">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="closeButton">Close</button>
                        <button type="submit" class="btn btn-primary submitButton" id="submitButton">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Another Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/qrgenrateedit.php" method="POST">
                    <div class="form-group">
                        <label for="username" id="usernameLabel">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status" class="statusLabel" id="statusLabel">Status</label>
                        <!--<select class="form-control status" id="status" name="status" required>-->
                        <!--    <option value="Work (No outing)">Work (No outing)</option>-->
                        <!--    <option value="Work (with outing)">Work (with outing)</option>-->
                        <!--    <option value="Stay near">Stay near</option>-->
                        <!--    <option value="No Work">No Work</option>-->
                        <!--</select>-->
<select class="form-control status" id="status" name="status" required>
                            <option value="Trabajo (sin salida)">Trabajo (sin salida)</option>
                            <option value="Trabajo (con Salida)">Trabajo (con Salida)</option>
                            <option value="Quedarse cerca (sin salida) ">Quedarse cerca (sin salida) </option>
                            <option value="Quedarse cerca (con salida)">Quedarse cerca (con salida)</option>
                            <option value="Sin trabajo">Sin trabajo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Arrival" class="arrivalLabel" id="arrivalLabel">Arrival time</label>
                        <select class="form-control arrival arrival2" id="arrival" name="arrival_time" required>
                            <option value="Before 8pm">Before 8pm</option>
                            <option value="8pm">8pm</option>
                            <option value="9pm">9pm</option>
                            <option value="10pm">10pm</option>
                        </select>
                    </div>
                    <div class="form-group" id="nullArrivalGroup2" style="display: none;">
                        <input type="text" class="form-control" id="nullArrival" value="Null" readonly>
                    </div>
                    <input type="hidden" id="studentId" name="studentId" value="">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary submitButton" id="submitButton" style="width: 100%;">Submit</button>
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

    
    
    // Logic for QRbutton on Mobile Screen Start
   document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to mobile QR code buttons
    document.querySelectorAll('.mobile-qr-code-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Get QR code URL and student name from data attributes
            const qrCodeUrl = this.getAttribute('data-qr-code');
            const studentName = this.getAttribute('data-student-name');

            // Update the QR code image in the modal
            const qrCodeImage = document.getElementById('mobileQrCodeImage');
            qrCodeImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrCodeUrl)}`;

            // Update the download link
            const downloadLink = document.getElementById('mobileDownloadQrCode');
            downloadLink.href = qrCodeImage.src;
            downloadLink.download = 'QRCode.png'; // Specify the filename for download

            // Update the title
            document.getElementById('mobileQrCodeTitle').textContent = `${studentName}'s QR Code`;

            // Show the mobile QR code display div
            document.getElementById('mobileQrCodeDisplay').style.display = 'block';
        });
    });

    // Add event listener to close button
    document.getElementById('closeMobileQrCodeDisplay').addEventListener('click', function() {
        // Hide the mobile QR code display div
        document.getElementById('mobileQrCodeDisplay').style.display = 'none';
    });
});


    // Logic for QRbutton on Mobile Screen End    
    

    
        // $(document).ready( function () {
        //     $('#studentTable').DataTable();
        // });

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

// Function to generate a random alphanumeric code
function generateRandomCode(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    return result;
}


function generateQrCode() {
    const qrImg = document.getElementById('qrImg');
    const generatedCodeInput = document.getElementById('generatedCode');
    const generatedCodeURLInput = document.getElementById('generatedCodeURL');
    const text = generateRandomCode(10); 
    const redicturl = "<?php echo $urlval?>endpoint/add-attendance.php?qr_code=" + text; 

    if (text.trim() === "") { 
        alert("Please enter text to generate a QR code.");
        return;
    } else {
        const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(redicturl)}`;

        // Set the QR code image source to the generated URL
        qrImg.src = apiUrl;

        // Update the hidden input fields with the generated code and URL
        generatedCodeInput.value = text;
        generatedCodeURLInput.value = redicturl;

        qrImg.onload = function() {
            // Show the modal close buttons and the QR code container
            document.querySelector('.modal-close').style.display = 'flex';
            document.querySelector('.qr-con').style.display = 'block';

            // Hide the Generate QR Code button
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



let currentLanguage = 'es'; // Set initial language to English

// Define dictionaries for different languages
const translations = {
    en: {
        workWithOuting: 'Work (no way out)',
        workNoOuting: 'Work (with Exit)',
        stayNear: 'Staying close (no way out)',
        stayingclose:'Staying close (with Exit)',
        noWork: 'No work'
    },
    es: {
        workWithOuting: 'Trabajo (sin salida)',
        workNoOuting: 'Trabajo (con Salida)',
        stayNear: 'Quedarse cerca (sin salida) ',
        stayingclose:'Quedarse cerca (con salida)',
        noWork: 'Sin trabajo'
    }
};

function loadLanguage(language) {
    fetch(`language/login/${language}.json`)
        .then(response => response.json())
        .then(data => {
            // Update text based on the selected language
            const elementById = {
                welcome: document.getElementById('welcome'),
                // listOfPresentEmployees: document.getElementById('listOfPresentEmployees'),
                tablename: document.getElementById('table-name'),
                tabledate: document.getElementById('table-date'),
                tableworktype: document.getElementById('table-worktype'),
                tableqrcode: document.getElementById('table-qrcode'),
                employee: document.getElementById('employee'),
                // tablegenqrcode: document.getElementById('generateQR'),
                previous: document.getElementById('studentTable_previous'),
                next: document.getElementById('studentTable_next'),
                showtext: document.getElementById('studentTable_info'),
                todayqrcode: document.getElementById('addStudent'),
                tabletime: document.getElementById('table-time'),
                workNameLabel: document.getElementById('workNameLabel'),
                statusLabel: document.getElementById('statusLabel'),
                arrivalLabel: document.getElementById('arrivalLabel'),
                closeButton: document.getElementById('closeButton'),
                generateQRButton: document.getElementById('generateQRButton'),
                editqr: document.getElementById('editStudentModalLabel'),
                takePicLabel: document.getElementById('takePicLabel'),
                qrtextdata:document.getElementById('qrtextt'),
                username:document.getElementById('usernameLabel'),
                notverfied:document.getElementById('notverfied')
            };

            // Update text for elements by ID
            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || 'No recuperar la recodificación en json';
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            // Update text for elements by class names
            const elementsByClass = {
                home: document.getElementsByClassName('home'),
                qrCode: document.getElementsByClassName('qrCode'),
                adminPanel: document.getElementsByClassName('adminPanel'),
                viewProfile: document.getElementsByClassName('viewProfile'),
                settings: document.getElementsByClassName('settings'),
                statusLabel: document.getElementsByClassName('statusLabel'),
                arrivalLabel: document.getElementsByClassName('arrivalLabel'),
                submitButton: document.getElementsByClassName('submitButton'),
                logout: document.getElementsByClassName('logout'),
                gallery: document.getElementsByClassName('gallery')
                // dataTables_empty:document.getElementsByClassName('dataTables_empty')
            };

            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerText = data[key] || '';
                }
            }

            // Update dynamic work type text based on language
            const workTypes = translations[language] || translations['en']; // Default to English if language is not found
            for (const [key, value] of Object.entries(workTypes)) {
                const elements = document.getElementsByClassName(key);
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerText = value;
                }
            }

            // Update status options
            const statusSelects = document.getElementsByClassName('status');
            Array.from(statusSelects).forEach(statusSelect => {
                statusSelect.innerHTML = ''; // Clear existing options
                for (const [key, value] of Object.entries(data.statusOptions || {})) {
                    const option = document.createElement('option');
                    option.value = key; // Use the key as value
                    option.text = value; // Use the value as the text
                    statusSelect.appendChild(option);
                }
            });
            
            

            // Update arrival options
            const arrivalSelects = document.getElementsByClassName('arrival');
            Array.from(arrivalSelects).forEach(arrivalSelect => {
                arrivalSelect.innerHTML = ''; // Clear existing options
                for (const [key, value] of Object.entries(data.arrivalOptions || {})) {
                    const option = document.createElement('option');
                    option.value = key; // Use the key as value
                    option.text = value; // Use the value as the text
                    arrivalSelect.appendChild(option);
                }
            });
            
            //             const arrivalSelects = document.getElementsByClassName('arrival2');
            // Array.from(arrivalSelects).forEach(arrivalSelect => {
            //     arrivalSelect.innerHTML = ''; // Clear existing options
            //     for (const [key, value] of Object.entries(data.arrivalOptions || {})) {
            //         const option = document.createElement('option');
            //         option.value = key; // Use the key as value
            //         option.text = value; // Use the value as the text
            //         arrivalSelect.appendChild(option);
            //     }
            // });
            
            
        })
        .catch(error => console.error('Error loading localization file:', error));
}

// Initial load
loadLanguage(currentLanguage);

// Handle language selection change
function handleLanguageChange(event) {
    const selectedLanguage = event.target.value;
    if (selectedLanguage !== currentLanguage) {
        currentLanguage = selectedLanguage;
        loadLanguage(selectedLanguage);
    }
}

// Attach event listeners for language selectors
document.getElementById('languageSelector').addEventListener('change', handleLanguageChange);
document.getElementById('mobilelanguageSelector').addEventListener('change', handleLanguageChange);



    </script>
    
</body>
</html>