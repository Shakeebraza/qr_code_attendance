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


    



<?php include_once('menu.php'); ?>

<section class="h-[100vh] lg:h-screen mx-auto flex justify-center items-center px-2" id="bg-black">
    <div class="w-full lg:w-3/4 bg-slate-100 p-8 rounded-lg shadow-xl">
        <h2 id="setting" class="text-2xl font-semibold text-center mb-6 text-gray-950">Settings</h2>
        
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
                        echo '<button class="absolute top-1 right-1 text-white bg-red-500 hover:bg-red-700 rounded-full w-6 h-6 flex items-center justify-center" onclick="removeImage(' . $image['imid'] . ')">✖</button>';
                        echo '</div>';
                    }
                    ?>
                    </div>
        </div>
    </div>
</section>
<?php include('script.php'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let currentLanguage = 'es';

    function loadLanguage(language) {
        fetch(`language/login/${language}.json`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                const elementById = {
                    welcome: document.getElementById('welcome'),
                    listOfPresentEmployees: document.getElementById('listOfPresentEmployees'),
                    settings: document.getElementById('setting'),
                    profileImage: document.getElementById('profileimage'),
                    password: document.getElementById('password'),
                    updateSettingsButton: document.getElementById('updatepassword')
                };

                for (const [key, element] of Object.entries(elementById)) {
                    if (element) {
                        element.innerText = data[key] || '';
                    } else {
                        console.warn(`Element with ID '${key}' not found.`);
                    }
                }

                const oldpasswordinput = document.getElementById('oldpasswordinput');
                const newpasswordinput = document.getElementById('newpasswordinput');
                if (oldpasswordinput) {
                    oldpasswordinput.placeholder = data.oldpassword || 'Old Password'; 
                }
                if (newpasswordinput) {
                    newpasswordinput.placeholder = data.newpassword || 'New Password'; 
                }

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
            })
            .catch(error => console.error('Error loading localization file:', error));
    }

    loadLanguage(currentLanguage);

    document.getElementById('languageSelector').addEventListener('change', function() {
        const selectedLanguage = this.value;
        if (selectedLanguage !== currentLanguage) {
            currentLanguage = selectedLanguage;
            loadLanguage(selectedLanguage);
        }
    });

    document.getElementById('mobilelanguageSelector').addEventListener('change', function() {
        const selectedLanguage = this.value;
        if (selectedLanguage !== currentLanguage) {
            currentLanguage = selectedLanguage;
            loadLanguage(selectedLanguage);
        }
    });
    
    
    let dropBox = document.getElementById('dropBox');


	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
		dropBox.addEventListener(evt, prevDefault, false);
	});
	function prevDefault (e) {
		e.preventDefault();
		e.stopPropagation();
	}

	['dragenter', 'dragover'].forEach(evt => {
		dropBox.addEventListener(evt, hover, false);
	});
	['dragleave', 'drop'].forEach(evt => {
		dropBox.addEventListener(evt, unhover, false);
	});
	function hover(e) {
		dropBox.classList.add('hover');
	}
	function unhover(e) {
		dropBox.classList.remove('hover');
	}


	dropBox.addEventListener('drop', mngDrop, false);
	function mngDrop(e) {
		let dataTrans = e.dataTransfer;
		let files = dataTrans.files;
		filesManager(files);
	}
function upFile(file) {
    let imageType = /image.*/;
    if (file.type.match(imageType)) {
        let url = '<?php echo $urlval; ?>ajax/uploadimage.php'; // Backend endpoint

        // Get user ID from the hidden input field
        let userId = $('#userId').val();

        let formData = new FormData();
        formData.append('file', file);
        formData.append('user_id', userId); // Append the user ID to the form data

        // Get current date and time
        let currentDate = new Date();
        formData.append('date', currentDate.toISOString().split('T')[0]); // yyyy-mm-dd
        formData.append('time', currentDate.toTimeString().split(' ')[0]); // hh:mm:ss

        // AJAX request to upload the image
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(result) {
                console.log('Success:', result);
                if (result.success) {
                    displaySuccess("Image uploaded successfully!"); // Display success message
                } else {
                    displayError(result.error); // Display the error message
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                displayError('An unexpected error occurred. Please try again.'); // Display generic error message
            }
        });
    } else {
        console.error("Only images are allowed!", file);
        displayError("Only images are allowed!"); // Display error for non-image files
    }
}

/**
 * Function to display error messages
 * @param {string} message - The error message to display
 */
function displayError(message) {
    displayMessage(message, 'error-message'); // Call displayMessage with error styling
}

/**
 * Function to display success messages
 * @param {string} message - The success message to display
 */
function displaySuccess(message) {
    displayMessage(message, 'success-message'); // Call displayMessage with success styling
}

/**
 * Function to display messages (both error and success)
 * @param {string} message - The message to display
 * @param {string} type - The type of message ('error-message' or 'success-message')
 */
function displayMessage(message, type) {
    // Create a div element for the message
    let messageDiv = $('<div class="' + type + '"></div>').text(message);
    $('body').append(messageDiv); // Append to body or a specific container

    // Style the message (optional)
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
        messageDiv.fadeOut(300, () => messageDiv.remove()); // Fade out and remove the message
    }, 60000);
}

function previewFile(file) {
    let imageType = /image.*/;
    if (file.type.match(imageType)) {
        let fReader = new FileReader();
        let gallery = document.getElementById('gallery');
        fReader.readAsDataURL(file);
        fReader.onloadend = function() {
            let wrap = document.createElement('div');
            wrap.className = "relative w-24 h-24 m-2 rounded-lg border border-gray-300 overflow-hidden"; // Square shape for mobile
            
            let img = document.createElement('img');
            img.src = fReader.result;
            img.className = "object-cover w-full h-full"; // Fill the square
            
            let imgCapt = document.createElement('p');
            let fSize = (file.size / 1000) + ' KB';
            imgCapt.innerHTML = `<span class="fName text-white">${file.name}</span><span class="fSize text-gray-400"> (${fSize})</span>`;
            
            // Delete icon
            let deleteIcon = document.createElement('button');
            deleteIcon.innerHTML = '✖'; // Cross icon
            deleteIcon.className = "absolute top-1 right-1 text-white bg-red-500 hover:bg-red-700 rounded-full w-6 h-6 flex items-center justify-center";
            deleteIcon.onclick = function() {
                gallery.removeChild(wrap);
            };
            
            wrap.appendChild(img);
            wrap.appendChild(deleteIcon);
            wrap.appendChild(imgCapt);
            gallery.appendChild(wrap);
        }
    } else {
        console.error("Only images are allowed!", file);
    }
}


	function filesManager(files) {

		files = [...files];

		files.forEach(upFile);
		files.forEach(previewFile);
	}
	
	
	function removeImage(imid) {
    // Hide the image div immediately
    document.getElementById('image-' + imid).style.display = 'none';

    // Send an AJAX request to delete the image
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "<?php echo $urlval?>ajax/delete_image.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define what happens when the request is successful
    xhr.onload = function () {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                console.log("Image deleted successfully");
            } else {
                alert("Failed to delete the image.");
                // If deletion fails, show the image again
                document.getElementById('image-' + imid).style.display = 'block';
            }
        }
    };

    // Send the image ID to the server
    xhr.send("imid=" + imid);
}
</script>


</html>
