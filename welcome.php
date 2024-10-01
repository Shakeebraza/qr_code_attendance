<?php
include_once('global.php');
$res = $funObject->checksession();



if ($res == 0) {
    header('Location: login.php');
    exit(); 
}
$chksAdmin = $funObject->isAdmin();
if($_SESSION['verified'] == 0){
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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        

        .modal-header, .modal-footer {
            border: none;
        }

        .modal-header h5 {
            color: #fff;
        }

        .modal-body input, .modal-footer button {
            background-color: #444;
            color: #fff;
        }

        .modal-body input::placeholder {
            color: #aaa;
        }

        .modal-footer button {
            background-color: #555;
        }

        .modal-footer button:hover {
            background-color: #666;
        }
        .bg-gray-200.text-gray-800 {
          background-color: #000000;
          color: white;
        }
        
    </style>
</head>
<body>
<?php
include_once('menu.php');
?>

<div class="w-full h-full lg:h-screen bg-gray-100 flex justify-center items-center p-4">
  <div class="w-full lg:w-4/5 h-4/5 shadow-lg rounded-lg bg-white grid grid-cols-1 lg:grid-cols-6 gap-4 p-4 mx-auto">

     <?php //if($chksAdmin != 0) {?>
      <?php if($chksAdmin == 4) {?>
    <div class="col-span-1 lg:col-span-2 h-full rounded-md shadow-md p-4 space-y-5 bg-gray-50 px-8">
      <h1 class="text-xl md:text-2xl font-bold tracking-tight text-gray-800">
        Scan Your QR Code Here
      </h1>
      <video id="interactive" class="h-48 md:h-96 w-full md:w-96 mx-auto bg-gray-300 rounded-lg mb-4" autoplay></video>
      <div id="qr-detected-container" class="qr-detected-container" style="display: none;">
        <form action="./endpoint/add-attendance.php" method="GET">
            <h4 class="text-center">Employee QR Detected!</h4>
            <input type="hidden" id="detected-qr-code" name="qr_code">
            <button type="submit" class="btn btn-dark form-control">Submit Attendance</button>
        </form>
      </div>
      <div class="flex flex-col gap-y-4 md:flex-row md:gap-x-5 justify-center items-center">
        <button id="toggle-camera" class="bg-gray-600 text-white px-4 py-2 text-base rounded-md shadow-sm hover:bg-gray-700 transition-transform transform hover:scale-105">
          Turn On Camera
        </button>
        <button id="switch-camera" class="border border-gray-600 text-gray-800 px-4 py-2 text-base rounded-md shadow-sm hover:bg-gray-100 hover:text-gray-900 transition-transform transform hover:scale-105">
          Switch Camera
        </button>
      </div>
    </div>
    <div class="col-span-1 lg:col-span-4 h-full rounded-md shadow-md p-4 space-y-5 bg-gray-50">
    <?php } else{ ?>
      <div class="col-span-1 lg:col-span-10 h-full rounded-md p-4 space-y-5">
    <?php } ?>
      <h1 class="text-xl md:text-2xl font-bold tracking-tight text-gray-800" id="listOfPresentEmployees">
        List of Today's Present Employees
      </h1>
<?php

try {

    $stmt = $conn->prepare("SELECT 
                                tbl_attendance.tbl_attendance_id,
                                users.username AS user_name,
                                tbl_attendance.room AS room,
                                users.email AS user_email,
                                users.profile AS user_profile,
                                users.id as userid,
                                tbl_student.tbl_student_id,
                                tbl_student.worktype,
                                tbl_student.worktypeeng,
                                DATE(tbl_attendance.time_in) AS attendance_date,
                                TIME(tbl_attendance.time_in) AS time_in
                            FROM tbl_attendance 
                            LEFT JOIN users ON users.id = tbl_attendance.tbl_user_id
                            LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id
                            WHERE DATE(tbl_attendance.time_in) = :currentDate");

    // Bind the current date
    $stmt->bindParam(':currentDate', $todayDate);

    // Execute the statement
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "An error occurred while processing your request.";
}
?>

<div class="overflow-x-auto">
    <table class="w-full text-left bg-white border border-gray-300 rounded-lg text-sm lg:text-base">
        <thead class="bg-gray-200 text-gray-800">
            <tr>
                <th class="p-2" id="table-id">ID</th>
                <th class="p-2" id="table-name">Name</th>
                <!--<th class="p-2" id="table-email">Email</th>-->
                <th class="p-2" id="table-profile">Profile</th>
                <th class="p-2" id="table-date">Date</th>
                <th class="p-2" id="table-testid">Test ID Update</th>
                <th class="p-2" id="table-cardid">ID Card Update</th>
                <th class="p-2" id="table-worktype">Tipo de trabajo</th>
                <th class="p-2" id="table-room">Room</th>
                <th class="p-2" id="table-time">Time</th>
                <?php
                if($_SESSION['type'] !=0){
                    echo '<th class="p-2" id="table-chnageworktype">Cambiar tipo de trabajo</th>';
                }
                
                ?>
                
                
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php 
            if ($result) {
                foreach ($result as $row) { 
                    // Apply the getCorrectedDateTime function to the time_in field
                    $correctedDateTimeString = $funObject->getCorrectedDateTime($row['attendance_date'] . ' ' . $row['time_in']);
                    $correctedDateTime = new DateTime($correctedDateTimeString);
                ?>
            <tr class="border-b border-gray-200 no-wrap">
                <td class="p-2"><?= htmlspecialchars($row['tbl_attendance_id']) ?></td>
                <td class="p-2"><?= htmlspecialchars($row['user_name']) ?></td>
                <!--<td class="p-2">-->
                    <!--<?= htmlspecialchars($row['user_email']) ?>-->
                <!--</td>-->
                <td class="p-2">
                    <?php if (!empty($row['user_profile'])): ?>
                        <img src="<?= htmlspecialchars($row['user_profile']) ?>" alt="Profile Picture" class="img-thumbnail rounded-full w-10 h-10 lg:w-14 lg:h-14">
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($urlval) ?>admin/img/user.jpg" alt="Profile Picture" class="img-thumbnail w-10 h-10 rounded-full lg:w-14 lg:h-14">
                    <?php endif; ?>
                </td>
                <td class="p-2"><?= htmlspecialchars($correctedDateTime->format('Y-m-d')) ?></td>
                <?php
                $filesData = $funObject->GetFilesDate($row['userid']);  
                $fileTypes = ['Test', 'Id card'];

                $uploadedTypes = [];

                if (!empty($filesData)) {
                    foreach ($filesData as $file) {
                        if (isset($file['input_type']) && in_array($file['input_type'], $fileTypes)) {
                            $uploadedTypes[$file['input_type']] = date($file["uploaded_at"]);
                        }
                    }

                    foreach ($fileTypes as $type) {
                        if (isset($uploadedTypes[$type])) {
                            $date = new DateTime($uploadedTypes[$type]);
                            echo '<td class="p-2">'.$date->format('Y-m-d').'</td>'; 
                        } else {
                            echo '<td class="p-2">No upload</td>';
                        }
                    }
                } else {
                    echo '<td class="p-2">No upload</td>';
                    echo '<td class="p-2">No upload</td>';
                }
                ?>
                <td class="p-2"><?= htmlspecialchars($row['worktype']) ?></td>
                <td class="p-2"><?= empty($row['room']) ? "waiting..." : htmlspecialchars($row['room']) ?></td>
                <td class="p-2"><?= htmlspecialchars($correctedDateTime->format('h:i A')) ?></td>
           
    <?php if ($_SESSION['type'] != 0) { ?>
    <td class="flex items-center justify-center h-[70px]">
        <div class="action-button">
            <button class="btn btn-primary btn-sm edit-worktype-btn" 
                    data-toggle="modal" 
                    data-target="#editStudentModal" 
                    data-worktype="<?= htmlspecialchars($row['worktypeeng']) ?>" 
                    data-student-id="<?= htmlspecialchars($row['tbl_student_id']) ?>">
                <i class="fa fa-edit"></i>
            </button>
        </div>
        </td>
    <?php } ?>

            </tr>
            <?php 
                } 
            }
            ?>


        </tbody>
    </table>
</div>
  </div>
</div>
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel">Edit Work Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="./endpoint/adminqrgenrateedit.php" method="POST">
                    <input type="hidden" id="studentId" name="studentId" value="" />
                    <div class="form-group">
                        <label for="status" class="statusLabel" id="statusLabel">Status</label>
                        <select class="form-control status" id="status" name="status" required>
                            <option value="workNoOuting">Work (No outing)</option>-->
                            <option value="workWithOuting">Work (with outing)</option>
                            <option value="stayNear">Stay near</option>
                            <option value="nohaytrabajo">No Work</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include('script.php');
?>

<script src="https://unpkg.com/instascan@latest"></script>
<script>
let currentLanguage = 'es';

function loadLanguage(language) {
    fetch(`language/login/${language}.json`)
        .then(response => response.json())
        .then(data => {

            // Update text based on the selected language
            const elementById = {
                welcome: document.getElementById('welcome'),
                listOfPresentEmployees: document.getElementById('listOfPresentEmployees'),
                tablename: document.getElementById('table-name'),
                tableemail: document.getElementById('table-email'),
                tableprofile: document.getElementById('table-profile'),
                tabledate: document.getElementById('table-date'),
                tabletestid: document.getElementById('table-testid'),
                tablecardid: document.getElementById('table-cardid'),
                tableroom: document.getElementById('table-room'),
                tabletime: document.getElementById('table-time'),
                statusLabel:document.getElementById('statusLabel')
            };

            // Update text for elements by ID
            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || '';
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            // Update text for elements with class names
            const elementsByClass = {
                home: document.getElementsByClassName('home'),
                qrCode: document.getElementsByClassName('qrCode'),
                adminPanel: document.getElementsByClassName('adminPanel'),
                viewProfile: document.getElementsByClassName('viewProfile'),
                settings: document.getElementsByClassName('settings'),
                logout: document.getElementsByClassName('logout'),
                gallery: document.getElementsByClassName('gallery')
            };

            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerText = data[key] || '';
                }
            }
            
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

        })
        .catch(error => console.error('Error loading localization file:', error));
}

// Load initial language
loadLanguage(currentLanguage);

// Handle language selection change for desktop selector
document.getElementById('languageSelector').addEventListener('change', function() {
    const selectedLanguage = this.value;
    if (selectedLanguage !== currentLanguage) {
        currentLanguage = selectedLanguage;
        loadLanguage(selectedLanguage);
    }
});

// Handle language selection change for mobile selector
document.getElementById('mobilelanguageSelector').addEventListener('change', function() {
    const selectedLanguage = this.value;
    if (selectedLanguage !== currentLanguage) {
        currentLanguage = selectedLanguage;
        loadLanguage(selectedLanguage);
    }
});

// let scanner;
// let cameraStarted = false;
// let cameras = [];
// let currentCameraIndex = 1;

// function startScanner(cameraIndex = 1) { // Default to back camera (index 1)
//     scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

//     scanner.addListener('scan', function (content) {
//         alert('QR Code detected: ' + content);
//         console.log(content);
//         document.getElementById('detected-qr-code').value = content; 
//         document.getElementById('qr-detected-container').style.display = 'block';
//         document.getElementById('interactive').style.display = 'none'; 
//         scanner.stop(); 
//         window.location.href = content;
//     });

//     Instascan.Camera.getCameras().then(function (availableCameras) {
//         cameras = availableCameras;

//         if (cameras.length > 1) {
//             scanner.start(cameras[cameraIndex]); 
//             if (cameraIndex === 0) { 
//                 document.getElementById('interactive').style.transform = 'scaleX(-1)'; // Mirror front camera view
//             } else {
//                 document.getElementById('interactive').style.transform = 'none'; // No mirroring for back camera
//             }
//         } else if (cameras.length > 0) {
//             scanner.start(cameras[0]); 
//             if (cameraIndex === 0) { 
//                 document.getElementById('interactive').style.transform = 'scaleX(-1)'; // Mirror front camera view
//             }
//         } else {
//             alert('No cameras found.');
//         }

//         cameraStarted = true;
//         document.getElementById('toggle-camera').innerText = 'Stop Camera';
//         currentCameraIndex = cameraIndex; 
//     }).catch(function (err) {
//         console.error('Camera access error:', err);
//         alert('Camera access error: ' + err);
//     });
// }

// function stopScanner() {
//     if (scanner) {
//         scanner.stop();
//         cameraStarted = false;
//         document.getElementById('toggle-camera').innerText = 'Start Camera';
//         document.getElementById('interactive').style.display = 'block'; 
//     }
// }

// function switchCamera() {
//     if (cameras.length > 1) {
//         let newCameraIndex = currentCameraIndex === 1 ? 0 : 1; // Toggle between front and back camera
//         stopScanner();
//         startScanner(newCameraIndex);
//     } else {
//         alert('Only one camera available.');
//     }
// }

// document.getElementById('toggle-camera').addEventListener('click', function () {
//     if (cameraStarted) {
//         stopScanner();
//     } else {
//         startScanner(currentCameraIndex); 
//     }
// });

// document.getElementById('switch-camera').addEventListener('click', switchCamera);

// document.addEventListener('DOMContentLoaded', function () {
//     startScanner(1); // Start with the back camera (index 1) when the page loads
// });

document.addEventListener('DOMContentLoaded', function() {
        // Get all edit buttons
        var editButtons = document.querySelectorAll('.edit-worktype-btn');
        
        // Loop through each button and add a click event
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Get the data attributes from the clicked button
                var worktype = this.getAttribute('data-worktype');
                var studentId = this.getAttribute('data-student-id');
                
                // Set the values in the modal
                document.querySelector('#status').value = worktype;  // Set work type
                document.querySelector('#studentId').value = studentId;  // Set student ID
            });
        });
    });

</script>
</body>
</html>
