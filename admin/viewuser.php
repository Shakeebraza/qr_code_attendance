<?php
include_once('header.php');

if (!isset($_GET['userid']) || empty($_GET['userid'])) {
    header('Location: ../login.php');
    exit(); 
} else {
    $id = base64_decode(base64_decode($_GET['userid']));

    $find = $funObject->FindUser($id);

    $files = $funObject->GetUserFiles($id); 
    if ($find['count'] < 1) {
        header('Location: ../login.php');
        exit(); 
    }

    if(!empty($find['records'][0]['profile'])){
        $imageUrl=$urlval.$find['records'][0]['profile'];
    }else{
        $imageUrl=$urlval.'admin/img/user.jpg';

    }
    if (!isset($_SESSION['type']) || $_SESSION['type'] == 0) {
        header('Location: ../login.php');
        exit();
    }
}

 
?>
<style>
.modal-content {
    background-color: #1a1a1a; /* Dark background */
    border-radius: 10px; /* Rounded corners */
    border: none; /* No border */
}

.modal-header {
    border-bottom: none; /* No border */
}

.modal-title {
    color: #fff; /* White text */
}

.close {
    color: #fff; /* White close button */
}

.modal-body {
    padding: 20px; /* Padding around the image */
}

.modal-body img {
    transition: transform 0.3s ease;
    border-radius: 5px; /* Slight rounding of image corners */
}

.modal-body img:hover {
    transform: scale(1.05); /* Slight zoom on hover */
}

.modal-backdrop {
    opacity: 0.7; /* Adjust backdrop opacity */
    background-color: #000; /* Dark backdrop */
}
#imageSlider {
  width: 50%;
}
.list-group-item.bg-dark.text-light {
  display: flex;
  justify-content: space-between;
}
.note {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
  justify-content: space-around;

}
.btn-warning:hover {
    filter: brightness(1.5);
    box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.8);
}
.profile-img {
    transition: transform 0.3s 
}
.profile-img:hover {
    cursor:pointer;
    transform: scale(1.2);
}
</style>
<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php echo $urval?>" id="homeatt">Home</a></li>
            <li class="breadcrumb-item active viewuser" aria-current="page">View User</li>
        </ol>
    </nav>

    <!-- User Details Container -->
    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4 viewuser" >View User</h6>
                <!-- Profile Image -->
                <div class="d-flex justify-content-center mb-4">
                    <img src="<?php echo $imageUrl; ?>" alt="Profile Image" class="rounded-circle profile-img" style="width: 100px; height: 100px;" data-toggle="modal" data-target="#imageModal">
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <div id="imageSlider" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            // Assuming $stmt and $images are defined and fetched as you described
                            $stmt = $conn->prepare("SELECT filepath, imid FROM gallery WHERE userid = :user_id");
                            $stmt->execute([':user_id' => $id]);
                            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                            // Loop through the images and display them in the carousel
                            foreach ($images as $index => $image) {
                                $activeClass = $index === 0 ? 'active' : ''; // Set first image as active
                                echo '<div class="carousel-item ' . $activeClass . '">';
                                echo '<img src="' . $urlval. htmlspecialchars($image['filepath']) . '" class="d-block w-100 " alt="Image ' . $image['imid'] . '">';
                                echo '</div>';
                            }
                            ?>
                    </div>
                    <a class="carousel-control-prev" href="#imageSlider" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#imageSlider" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
                <div class="mb-3">
                    <label for="username" class="form-label" id="labusername">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo $find['records'][0]['username']; ?>" readonly>
                </div>
                <?php if($_SESSION['type'] == 2): ?>
                <div class="mb-3">
                    <label for="actual_name" class="form-label" id="labactialname">Actual Name</label>
                    <input type="text" class="form-control" id="actual_name" value="<?php echo htmlspecialchars($find['records'][0]['actual_name'] ?? 'Not Set Actual Name'); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="work_name" class="form-label" id="labworkname">Work Name</label>
                    <input type="text" class="form-control" id="work_name" value="<?php echo htmlspecialchars($find['records'][0]['work_name'] ?? 'Not Set Work Name'); ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label" id="labnumber">Number</label>
                    <input type="email" class="form-control" id="email" value="<?php echo $find['records'][0]['email']; ?>" readonly>
                </div>
                <?php endif; ?>
                <!--<div class="mb-3">-->
                <!--    <label for="status" class="form-label" id="labstatus">Status</label>-->
                <!--    <input type="text" class="form-control" id="status" value="<?php //echo $find['records'][0]['type'] == 0 ? 'Deactive' : 'Active'; ?>" readonly>-->
                <!--</div>-->
                <div class="mb-3">
                    <label for="usertype" class="form-label" id="labusertype">User Type</label>
                    <input type="text" class="form-control" id="usertype" value="<?php echo $find['records'][0]['type'] == 0 ? 'User' : 'Supervisor'; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="verified" class="form-label" id="labver">Verified</label>
                    <input type="text" class="form-control" id="verified" value="<?php echo $find['records'][0]['verified'] == 0 ? 'Not Verified' : 'Verified'; ?>" readonly>
                </div>
                

                <!-- User Files -->
                <div class="mb-3">
                    <label for="userfiles" class="form-label" id="labUploaded">Uploaded Files</label>
                    <ul class="list-group">
                        <?php if ($files['count'] > 0) : ?>
                            <?php foreach ($files['records'] as $file) : ?>
                                <li class="list-group-item bg-dark text-light">
                                    <a href="<?php echo $urlval.$file['file_path']; ?>" target="_blank" class="text-light">
                                        <?php echo $file['filename']; ?>
                                    </a>
                                    <p><?php echo date("d-M-y", strtotime($file['uploaded_at'])); ?></p>
                                </li>
                                
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item bg-dark text-light" id="labnoUploaded">No files uploaded.</li>
                            
                        
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML Code -->
<div class="container-fluid pt-4 px-4">
    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <!-- Notes Section for Admin/Supervisor -->
                <?php if ($_SESSION['type'] == 1 || $_SESSION['type'] == 2): ?>
                    <div class="mb-4">
                        <h6 class="mb-3">Admin/Supervisor Notes</h6>
                        <div id="userNotes">
                            <!-- Notes will be dynamically loaded here -->
                        </div>
                        <button class="btn btn-success mb-2" data-toggle="modal" data-target="#addNoteModal">Add Note</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<!-- Modal for Adding/Editing Notes -->
<div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Add/Edit Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="noteForm">
                    <input type="hidden" id="noteId" name="noteId" value="">
                    <div class="form-group">
                        <label for="noteText">Note</label>
                        <textarea class="form-control" id="noteText" name="noteText" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </form>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background-color: #1a1a1a; border-radius: 10px;">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="imageModalLabel" style="color: #fff;">Imagen de perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color: #fff;">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img src="<?php echo $imageUrl; ?>" alt="Profile Image" class="img-fluid rounded" style="max-height: 500px; border: 2px solid #fff;">
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>
<script>


       let currentLanguage = 'es';

    function loadLanguage(language) {
        fetch(`<?php echo $urlval ?>language/admin/${language}.json`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                // Update text based on the selected language for elements by ID
                const elementById = {
                    gowebsite: document.getElementById('gowebsite'),
                    myprofile: document.getElementById('myprofile'),
                    settings: document.getElementById('settings'),
                    logout: document.getElementById('logout'),
                    dashboard: document.getElementById('dashboard'),
                    todayattendance: document.getElementById('todayattendance'),
                    filterattendance: document.getElementById('filterattendance'),
                    qrtoday: document.getElementById('qrtoday'),
                    Users: document.getElementById('Users'),
                    alluser: document.getElementById('alluser'),
                    adduser: document.getElementById('adduser'),
                    room: document.getElementById('room'),
                    addroom: document.getElementById('addroom'),
                    allroom: document.getElementById('allroom'),
                    admin: document.getElementById('useradmin'),
                    copyright: document.getElementById('copyright'),
                    allrightreserved: document.getElementById('allrightreserved'),
                    designedby: document.getElementById('designedby'),
                    
                    // dashboard data
                    home: document.getElementById('homeatt'),
                    
                    // New Labels
                    labusername: document.getElementById('labusername'),
                    labactialname: document.getElementById('labactialname'),
                    labworkname: document.getElementById('labworkname'),
                    labnumber: document.getElementById('labnumber'),
                    labstatus: document.getElementById('labstatus'),
                    labusertype: document.getElementById('labusertype'),
                    labver: document.getElementById('labver'),
                    labUploaded: document.getElementById('labUploaded'),
                    labnoUploaded: document.getElementById('labnoUploaded')
                };

                // Update text for elements by ID
                for (const [key, element] of Object.entries(elementById)) {
                    if (element) {
                        element.innerText = data[key] || '';
                    } else {
                        console.warn(`Element with ID '${key}' not found.`);
                    }
                }
                
            const elementsByClass = {
                viewuser: document.getElementsByClassName('viewuser'),
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'viewuser') {
                        elements[i].innerText = data[key] || '';
                    }
                }
            }

            })
            .catch(error => console.error('Error loading localization file:', error));
    }

    // Load initial language
    loadLanguage(currentLanguage);

    // Handle language selection change
    document.getElementById('languageSelector').addEventListener('change', function() {
        const selectedLanguage = this.value;
        if (selectedLanguage !== currentLanguage) {
            currentLanguage = selectedLanguage;
            loadLanguage(selectedLanguage);
        }
    });


    // This code sets the image source in the modal
    $('.rounded-circle').on('click', function() {
        var src = $(this).attr('src');
        $('#imageModal img').attr('src', src);
        $('#imageModal').modal('show');  // Explicitly show the modal
    });

$('.close').on('click', function() {
        // You can add any additional functionality here before closing
        console.log('Modal is closing...'); // Example: logging to the console

        // Close the modal
        $('#imageModal').modal('hide'); // Hides the modal
         $('#addNoteModal').modal('hide');
    });


 // Function to load existing notes
    function loadNotes() {
        $.ajax({
            url: 'note.php',
            type: 'POST',
            data: {
                action: 'load',
                userId: '<?php echo $id; ?>' // Assuming $id is defined in your PHP code
            },
            dataType: 'json',
            success: function(response) {
                let notesHtml = '';
                response.forEach(note => {
                    notesHtml += `
                        <div class="note">
                            <p>${note.note_text}</p>
                            <div style="display: flex; justify-content: space-between;width:100%;">
                            <small>Added by: ${note.created_by} on ${note.created_at}</small>
                            <button class="btn btn-sm btn-warning editNote" data-id="${note.note_id}" data-text="${note.note_text}">Edit</button>
                          </div>
                        </div>
                    `;
                });
                $('#userNotes').html(notesHtml);
            }
        });
    }

    // Initial load
    loadNotes();

    // Submit new note
    $('#noteForm').on('submit', function(e) {
        e.preventDefault();

        let noteText = $('#noteText').val();
        let noteId = $('#noteId').val();

        let action = noteId ? 'edit' : 'add'; // Determine action based on noteId

        $.ajax({
            url: 'note.php',
            type: 'POST',
            data: {
                action: action,
                userId: '<?php echo $id; ?>',
                noteText: noteText,
                noteId: noteId // Send noteId for editing
            },
            success: function(response) {
                $('#noteText').val(''); // Clear textarea
                $('#noteId').val(''); // Clear noteId
                $('#addNoteModal').modal('hide'); // Close modal
                loadNotes(); // Reload notes
            }
        });
    });


    // Edit note function
    $(document).on('click', '.editNote', function() {
        let noteId = $(this).data('id');
        let noteText = $(this).data('text');

        $('#noteId').val(noteId);
        $('#noteText').val(noteText);
        $('#addNoteModal').modal('show');
       
    });
</script>
</body>
</html>
