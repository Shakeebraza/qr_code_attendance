<?php
include_once('global.php'); 

$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit();
}
$userId = $_SESSION['user_id'];

// Fetch images from the database
$stmt = $conn->prepare("SELECT filepath,imid FROM gallery WHERE userid = :user_id");
$stmt->execute([':user_id' => $userId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(!empty($images)){
        header('Location: empmasterlist.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');
        * {
            font-family: 'Poppins', sans-serif;
        }
        /* Add this to your stylesheet, if needed */
#gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem; /* Space between items */
}
#bg-black {
  background-color: #000000e3;
}

    </style>
</head>


    



<body>

<section class="h-[100vh] lg:h-screen mx-auto flex justify-center items-center px-2" id="bg-black">
    <div class="w-full lg:w-3/4 bg-slate-100 p-8 rounded-lg shadow-xl">
        <h2 id="setting" class="text-2xl font-semibold text-center mb-6 text-gray-950">Ajustes</h2>
        <p class="text-center mb-6 text-gray-950">Sube 2 o más imágenes de tu single de carga, no podemos ejecutarla</p>
        <div id="dropBox" class="w-full border-4 border-dashed border-gray-600 bg-slate-100 rounded-lg  text-center p-4 mb-6 hover:border-gray-400">
            <p class="text-gray-950">Arrastre y suelte imágenes aquí...</p>
            <form class="w-full mt-4" enctype="multipart/form-data">
                <input type="hidden" id="userId" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="file" id="imgUpload" multiple accept="image/*" class="hidden" onchange="filesManager(this.files)">
                <label for="imgUpload" class="cursor-pointer text-white bg-blue-500 hover:bg-blue-700 px-4 py-2 rounded-lg text-sm lg:text-base">Subir aquí</label>
               
            </form>
            <div id="gallery" class="mt-4 mx-auto ">
                                        <?php
                    foreach ($images as $image) {
                        echo '<div class="relative w-24 h-24 m-2 rounded-lg border border-gray-300 overflow-hidden" id="image-'.$image['imid'].'">';
                        echo '<img src="' . $urlval . htmlspecialchars($image['filepath']) . '" class="object-cover w-full h-full" />';
                        // echo '<button class="absolute top-1 right-1 text-white bg-red-500 hover:bg-red-700 rounded-full w-6 h-6 flex items-center justify-center" onclick="removeImage(' . $image['imid'] . ')">✖</button>';
                        echo '</div>';
                    }
                    ?>
                    </div>
        </div>
         <p class="text-center mb-6 text-gray-950" id="infoMessage"></p>
    </div>
</section>
<?php include('script.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    
let dropBox = document.getElementById('dropBox');
let selectedFiles = []; // Store selected images
let messageTimeout = null; // To store the timeout reference for the message

// Prevent default behavior for drag events
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
    dropBox.addEventListener(evt, prevDefault, false);
});

// Prevent default behavior function
function prevDefault(e) {
    e.preventDefault();
    e.stopPropagation();
}

// Add hover effects
['dragenter', 'dragover'].forEach(evt => {
    dropBox.addEventListener(evt, hover, false);
});
['dragleave', 'drop'].forEach(evt => {
    dropBox.addEventListener(evt, unhover, false);
});

// Hover effect functions
function hover(e) {
    dropBox.classList.add('hover');
}
function unhover(e) {
    dropBox.classList.remove('hover');
}

// Manage drop event
dropBox.addEventListener('drop', mngDrop, false);
function mngDrop(e) {
    let dataTrans = e.dataTransfer;
    let files = dataTrans.files;
    filesManager(files);
}

// Function to handle files and trigger AJAX if both images are selected
function filesManager(files) {
    files = [...files];
    
    // Loop through the dropped files
    files.forEach((file) => {
        if (selectedFiles.length < 2 && file.type.match(/image.*/)) {
            selectedFiles.push(file); // Add image to the selected files array
            previewFile(file); // Preview the file

            // Show the message if only one image is uploaded
            if (selectedFiles.length === 1) {
                displayInfo("Por favor sube una imagen más");

                // Hide the message after 25 seconds
                clearTimeout(messageTimeout);
                messageTimeout = setTimeout(() => {
                    removeInfo(); // Hide message
                }, 25000); // 25 seconds
            }

            // If 2 images have been selected, trigger the AJAX upload
            if (selectedFiles.length === 2) {
                removeInfo(); // Hide message before upload
                uploadImages(selectedFiles); // Trigger the upload function
            }
        } else if (!file.type.match(/image.*/)) {
            displayError("Only images are allowed!");
        }
    });
}

// Function to upload images together
function uploadImages(files) {
    let url = '<?php echo $urlval; ?>ajax/uploadimage.php'; // Backend endpoint

    // Get user ID from the hidden input field
    let userId = $('#userId').val();
    let formData = new FormData();

    // Append each image file to the FormData
    files.forEach((file) => {
        formData.append('file[]', file); // Use 'file[]' for multiple files
    });

    // Add additional data like user ID, date, etc.
    let currentDate = new Date();
    formData.append('user_id', userId);
    formData.append('date', currentDate.toISOString().split('T')[0]); // yyyy-mm-dd
    formData.append('time', currentDate.toTimeString().split(' ')[0]); // hh:mm:ss

    // AJAX request to upload both images
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(result) {
            if (result.success) {
                console.log(result.success);
                // Redirect if successful
                setTimeout(function() {
                    window.location.href = '<?php echo $urlval; ?>empmasterlist.php';
                }, 3000); 
            } else {
                displayError(result.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            displayError('An unexpected error occurred. Please try again.');
        }
    });
}

// Function to preview the file (image)
function previewFile(file) {
    let imageType = /image.*/;
    if (file.type.match(imageType)) {
        let fReader = new FileReader();
        let gallery = document.getElementById('gallery');
        fReader.readAsDataURL(file);
        fReader.onloadend = function() {
            let wrap = document.createElement('div');
            wrap.className = "relative w-24 h-24 m-2 rounded-lg border border-gray-300 overflow-hidden";
            
            let img = document.createElement('img');
            img.src = fReader.result;
            img.className = "object-cover w-full h-full";
            
            // let deleteIcon = document.createElement('button');
            // deleteIcon.innerHTML = '✖';
            // deleteIcon.className = "absolute top-1 right-1 text-white bg-red-500 hover:bg-red-700 rounded-full w-6 h-6 flex items-center justify-center";
            // deleteIcon.onclick = function() {
            //     gallery.removeChild(wrap);
            //     selectedFiles = selectedFiles.filter(f => f.name !== file.name); // Remove file from selectedFiles array
            // };
            
            wrap.appendChild(img);
            // wrap.appendChild(deleteIcon);
            gallery.appendChild(wrap);
        };
    } else {
        displayError("Only images are allowed!");
    }
}

function displayInfo(message) {
    // Select the container where you want to append the message
    let container = document.querySelector('.bg-secondary'); // Adjust this to your desired container
    let infoMessage = document.getElementById('infoMessage');

    if (!infoMessage) {
        infoMessage = document.createElement('p');
        infoMessage.id = 'infoMessage';
        infoMessage.style.color = 'blue';
        infoMessage.style.margin = '10px 0'; // Add margin for better spacing
        infoMessage.style.fontSize = '14px'; // Adjust the font size for visibility
        container.appendChild(infoMessage); // Append to the specific container
    }

    infoMessage.innerHTML = message;
}

// Function to remove info message
function removeInfo() {
    let infoMessage = document.getElementById('infoMessage');
    if (infoMessage) {
        infoMessage.remove();
    }
}

// Function to display error messages
function displayError(message) {
    displayMessage(message, 'error-message');
}

// Function to display success messages
function displaySuccess(message) {
    displayMessage(message, 'success-message');
}

// Function to display messages
function displayMessage(message, type) {
    let messageDiv = $('<div class="' + type + '"></div>').text(message);
    $('body').append(messageDiv);

    if (type === 'error-message') {
        messageDiv.css({
            'position': 'fixed',
            'top': '10px',
            'right': '10px',
            'background-color': 'red',
            'color': 'white',
            'padding': '10px',
            'border-radius': '5px',
            'z-index': '1000'
        });
    } else if (type === 'success-message') {
        messageDiv.css({
            'position': 'fixed',
            'top': '10px',
            'right': '10px',
            'background-color': 'green',
            'color': 'white',
            'padding': '10px',
            'border-radius': '5px',
            'z-index': '1000'
        });
    }

    // Hide the message after 1 minute (60000 milliseconds)
    setTimeout(() => {
        messageDiv.fadeOut(300, () => messageDiv.remove());
    }, 60000);
}

</script>

</body>
</html>
