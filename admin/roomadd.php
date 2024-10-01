<?php
include_once('header.php');
?>

<div class="container-fluid pt-4 px-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
            <li class="breadcrumb-item active addroom" aria-current="page">Add Room</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4 addroom">Add Room</h6>
                <form id="addRoomForm">
                    <div class="mb-3">
                        <label for="name" class="form-label" id="labroom">Room Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label" id="lenenable">Enable</label>
                        <select class="form-select" id="status" name="enable">
                            <option id="act" value="1">Active</option>
                            <option id="dea" value="0">Deactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary addroom">Add Room</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addRoomForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo $urlval ?>ajax/addroom.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    alert('Room added successfully!');
                    $('#addRoomForm')[0].reset();
                } else {
                    alert('Error adding room: ' + jsonResponse.message);
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
                    home: document.getElementById('homeatt'),
                    admin: document.getElementById('adminatt'),
                    labroom: document.getElementById('labroom'),
                    enable: document.getElementById('lenenable'),
                    tableaction: document.getElementById('tableaction'),
                    active:document.getElementById('act'),
                    deactive:document.getElementById('dea'),
               
                    updatesala:document.getElementById('updatesala')
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
                addroom: document.getElementsByClassName('addroom'),
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'addroom') {
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
