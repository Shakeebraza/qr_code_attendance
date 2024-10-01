<?php
include_once('header.php');

?>

<div class="container-fluid pt-4 px-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
            <li class="breadcrumb-item active adduser" aria-current="page adduser">Add User</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4 adduser">Add User</h6>
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="username" class="form-label" id="labusername">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label" id="labnumber">Number</label>
                        <input type="number" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label" id="labpassword">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                     <?php if($_SESSION['type'] == 2) :?>
                    <div class="mb-3">
                        <label for="type" class="form-label" id="labusertype">User Type</label>
                        <select class="form-select" id="type" name="type">
                            <option id="seluser" value="0">User</option>
                            <option id="selsup" value="1">Supervisor</option>
                        </select>
                    </div>
                    <?php else :?>
                    <div class="mb-3">
                        <label for="type" class="form-label" id="labusertype">User Type</label>
                        <select class="form-select" id="type" name="type">
                            <option id="seluser" value="0">User</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="status" class="form-label" id="labstatus">Status</label>
                        <select class="form-select" id="status" name="verified">
                            <option id="dea" value="0">Deactive</option>
                            <option id="act" value="1">Active</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label" id="labUploaded">Upload Files</label>
                        <input class="form-control bg-dark text-light" type="file" id="formFileMultiple" name="profile" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary adduser">Add User</button>
                </form>
            </div>
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
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo $urlval?>ajax/add-user.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response === 'success') {
                    alert('User added successfully!');
                    $('#addUserForm')[0].reset();
                } else {
                    alert('Error adding user: ' + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('AJAX error: ' + textStatus);
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
                     active:document.getElementById('act'),
                    deactive:document.getElementById('dea'),
                    seluser:document.getElementById('seluser'),
                    selsup:document.getElementById('selsup')
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
                adduser: document.getElementsByClassName('adduser'),
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'adduser') {
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
