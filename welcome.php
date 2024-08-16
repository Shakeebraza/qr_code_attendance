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
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        .modal-content {
            background-color: #333;
            color: #fff;
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
        
    </style>
</head>
<body>
<?php
include_once('menu.php');
?>
<div class="w-full h-full lg:h-screen bg-gray-100 flex justify-center items-center p-4">
  <div class="w-full h-4/5 max-w-4xl lg:max-w-7xl shadow-lg rounded-lg bg-white grid grid-cols-1 lg:grid-cols-6 gap-4 p-4 mx-auto">

     <?php if($chksAdmin != 0) {?>
    <div class="col-span-1 lg:col-span-2 h-full rounded-md shadow-md p-4 space-y-5 bg-gray-50 px-8">
      <h1 class="text-xl md:text-2xl font-bold tracking-tight text-gray-800">
        Scan Your QR Code Here
      </h1>
      <video id="interactive" class="h-48 md:h-96 w-full md:w-96 mx-auto bg-gray-300 rounded-lg mb-4" autoplay></video>
      <div id="qr-detected-container" class="qr-detected-container" style="display: none;">
        <form action="./endpoint/add-attendance.php" method="POST">
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
      <div class="col-span-1 lg:col-span-10 h-full rounded-md shadow-md p-4 space-y-5 bg-gray-50">
    <?php } ?>
      <h1 class="text-xl md:text-2xl font-bold tracking-tight text-gray-800">
        List of Today's Present Employees
      </h1>
<?php
// Set the current date
$currentDate = date('Y-m-d');

try {
    // Prepare the SQL query to fetch attendance records for the current date
    $stmt = $conn->prepare("SELECT 
                                tbl_attendance.tbl_attendance_id,
                                users.username AS user_name,
                                tbl_attendance.room AS room,
                                users.email AS user_email,
                                users.profile AS user_profile,
                                DATE(tbl_attendance.time_in) AS attendance_date,
                                TIME(tbl_attendance.time_in) AS time_in
                            FROM tbl_attendance 
                            LEFT JOIN users 
                            ON users.id = tbl_attendance.tbl_user_id 
                            WHERE DATE(tbl_attendance.time_in) = :currentDate");
    // Bind the current date parameter
    $stmt->bindParam(':currentDate', $currentDate);
    // Execute the query
    $stmt->execute();
    // Fetch the results as an associative array
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log any database errors and display a generic error message
    error_log("Database error: " . $e->getMessage());
    echo "An error occurred while processing your request.";
}
?>

<!-- HTML Table to Display Attendance Records -->
<div class="overflow-x-auto">
    <table class="w-full text-left bg-white border border-gray-300 rounded-lg">
        <thead class="bg-gray-200 text-gray-800">
            <tr>
                <th class="p-2">ID</th>
                <th class="p-2">Name</th>
                <th class="p-2">Email</th>
                <th class="p-2">Profile</th>
                <th class="p-2">Date</th>
                <th class="p-2">Room</th>
                <th class="p-2">Time</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($result as $row) { ?>
            <tr class="border-b border-gray-200">
                <td class="p-2"><?= htmlspecialchars($row['tbl_attendance_id']) ?></td>
                <td class="p-2"><?= htmlspecialchars($row['user_name']) ?></td>
                <td class="p-2"><?= htmlspecialchars($row['user_email']) ?></td>
                <td class="p-2">
                    <?php if (!empty($row['user_profile'])): ?>
                        <img src="<?= htmlspecialchars($row['user_profile']) ?>" alt="Profile Picture" class="img-thumbnail rounded-circle" style="max-width: 100px; max-height: 62px;">
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($urlval) ?>admin/img/user.jpg" alt="Profile Picture" class="img-thumbnail rounded-circle" style="max-width: 100px; max-height: 62px;">
                    <?php endif; ?>
                </td>
                <td class="p-2"><?= htmlspecialchars($row['attendance_date']) ?></td>
                <td class="p-2"><?= empty($row['room']) ? "Waiting..." : htmlspecialchars($row['room']) ?></td>
                <td class="p-2"><?= htmlspecialchars($row['time_in']) ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

  </div>
</div>

<?php
include('script.php');
?>

<script src="https://unpkg.com/instascan@latest"></script>
<script>
let scanner;
let cameraStarted = false;
let cameras = [];
let currentCameraIndex = 1;

function startScanner(cameraIndex = 1) { // Default to back camera (index 1)
    scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

    scanner.addListener('scan', function (content) {
        alert('QR Code detected: ' + content);
        console.log(content);
        document.getElementById('detected-qr-code').value = content; 
        document.getElementById('qr-detected-container').style.display = 'block';
        document.getElementById('interactive').style.display = 'none'; 
        scanner.stop(); 
    });

    Instascan.Camera.getCameras().then(function (availableCameras) {
        cameras = availableCameras;

        if (cameras.length > 1) {
            scanner.start(cameras[cameraIndex]); 
            if (cameraIndex === 0) { 
                document.getElementById('interactive').style.transform = 'scaleX(-1)'; // Mirror front camera view
            } else {
                document.getElementById('interactive').style.transform = 'none'; // No mirroring for back camera
            }
        } else if (cameras.length > 0) {
            scanner.start(cameras[0]); 
            if (cameraIndex === 0) { 
                document.getElementById('interactive').style.transform = 'scaleX(-1)'; // Mirror front camera view
            }
        } else {
            alert('No cameras found.');
        }

        cameraStarted = true;
        document.getElementById('toggle-camera').innerText = 'Stop Camera';
        currentCameraIndex = cameraIndex; 
    }).catch(function (err) {
        console.error('Camera access error:', err);
        alert('Camera access error: ' + err);
    });
}

function stopScanner() {
    if (scanner) {
        scanner.stop();
        cameraStarted = false;
        document.getElementById('toggle-camera').innerText = 'Start Camera';
        document.getElementById('interactive').style.display = 'block'; 
    }
}

function switchCamera() {
    if (cameras.length > 1) {
        let newCameraIndex = currentCameraIndex === 1 ? 0 : 1; // Toggle between front and back camera
        stopScanner();
        startScanner(newCameraIndex);
    } else {
        alert('Only one camera available.');
    }
}

document.getElementById('toggle-camera').addEventListener('click', function () {
    if (cameraStarted) {
        stopScanner();
    } else {
        startScanner(currentCameraIndex); 
    }
});

document.getElementById('switch-camera').addEventListener('click', switchCamera);

document.addEventListener('DOMContentLoaded', function () {
    startScanner(1); // Start with the back camera (index 1) when the page loads
});
</script>
</body>
</html>
