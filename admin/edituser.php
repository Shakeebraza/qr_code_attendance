<?php
include_once('header.php');

// Check if 'userid' is set and not empty
if (!isset($_GET['userid']) || empty($_GET['userid'])) {
    header('Location: ../login.php');
    exit(); 
} else {
    // Decode the user ID
    $id = base64_decode($_GET['userid']);

    $find = $funObject->FindUser(base64_decode($id));
    if($find['records'][0]['type'] == 2){
        header('Location: ../login.php');
        exit();
    }
    if ($find['count'] < 1) {
        header('Location: ../login.php');
        exit(); 
    }
    if(!empty($find['records'][0]['profile'])){
        $imageUrl=$urlval.$find['records'][0]['profile'];
    }else{
        $imageUrl=$urlval.'admin/img/user.jpg';

    }
}
?>

<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
            <li class="breadcrumb-item active edituser" aria-current="page">Edit User</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-center">
    <div class="col-sm-12 col-xl-6">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4 edituser">Edit User</h6>
            <!-- Profile Image -->
            <div class="d-flex justify-content-center mb-4">
                <img src="<?php echo $imageUrl; ?>" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
            </div>
            <form id="editUserForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label" id="labusername">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $find['records'][0]['username']; ?>">
                    <input type="hidden" id="userId" name="userId" value="<?php echo base64_decode($id); ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label" id="labnumber">Number</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" value="<?php echo $find['records'][0]['email']; ?>">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label" id="labpassword">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <div class="mb-3">
                    <label for="exampleInputRePassword1" class="form-label" id="labrepassword">Re-Password</label>
                    <input type="password" class="form-control" id="exampleInputRePassword1" name="repassword">
                </div>
                <div class="mb-3">
                    <label for="exampleSelect1" class="form-label" id="labstatus">Status</label>
                    <select class="form-select" id="status" name="status" aria-label="Default select example">
                        <option id="dea" value="0" <?php if ($find['records'][0]['type'] == 0) echo "selected"; ?>>Deactive</option>
                        <option id="act" value="1" <?php if ($find['records'][0]['type'] == 1) echo "selected"; ?>>Active</option>
                    </select>
                </div>
                <?php if($_SESSION['type'] == 2) :?>
                <div class="mb-3">
                    <label for="exampleSelect2" class="form-label" id="labusertype">User Type</label>
                    <select class="form-select" id="usertype" name="usertype" aria-label="Default select example">
                        <option id="seluser" value="0" <?php if ($find['records'][0]['verified'] == 0) echo "selected"; ?>>User</option>
                        <option id="selsup" value="1" <?php if ($find['records'][0]['type'] == 1) echo "selected"; ?>>Supervisor</option>
                    </select>
                </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label" id="labUploaded">Upload Files</label>
                    <input class="form-control bg-dark text-light" type="file" id="formFileMultiple" name="files" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary" id="update">Update Profile</button>
            </form>
        </div>
    </div>
</div>
 <script>
$(document).ready(function() {

    $('#username').on('input', function() {
        var input = $(this).val();
        var validInput = input.replace(/[^a-zA-Z\s]/g, '');
        $(this).val(validInput);
    });

    $('#editUserForm').submit(function(event) {
        event.preventDefault(); 

        var formData = new FormData(this); 

        $.ajax({
            url: '../ajax/edituser.php', 
            type: 'POST',
            data: formData,
            contentType: false, 
            processData: false,
            success: function(response) {
                alert('User updated successfully!');
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});


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
                    labnoUploaded: document.getElementById('labnoUploaded'),
                    password:document.getElementById('labpassword'),
                    repassword:document.getElementById('labrepassword'),
                    updateProfile:document.getElementById('update'),
                    active:document.getElementById('act'),
                    deactive:document.getElementById('dea'),
                    seluser:document.getElementById('seluser'),
                    selsup:document.getElementById('selsup'),
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
                edituser: document.getElementsByClassName('edituser'),
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'edituser') {
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

 </script>

<?php
include_once('footer.php');
?>
</body>
</html>
