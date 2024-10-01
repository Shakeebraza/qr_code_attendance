<?php
include_once('header.php');
// $data = $funObject->GetRooms(); // Updated to GetRooms
?>

<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>admin" id="adminatt">Admin</a></li>
                    <li class="breadcrumb-item active allromm" aria-current="page">All Rooms</li>
                </ol>
            </nav>

            <h6 class="mb-4 allromm">All Rooms</h6>

            <div class="table-responsive">
                <table id="rooms-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" id="tablename">Name</th>
                            <th scope="col"id="tablestatus">status</th>
                            <th scope="col" id="tableaction">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#rooms-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo $urlval; ?>ajax/fetch_rooms.php",
            "type": "POST",
            "data": function(d) {
                d.search = $('input[name="search"]').val();
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "enabled" },
            { "data": "action", "sortable": false } 
        ],
           "language": {
            "emptyTable": "No hay datos disponibles en la tabla",
            "zeroRecords": "No se encontraron registros coincidentes",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                        "previous": "Anterior"
                    }
        },
    });

    // Handle form submission
    $('form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });

    // Handle edit button click
    $('#rooms-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        alert('Edit button clicked for ID: ' + id);
        // Implement your edit functionality here
    });

    // Handle delete button click
    $('#rooms-table').on('click', '.delete-btn', function() {
        var id = $(this).data('id');

        if (confirm('Are you sure you want to delete this room?')) {
            $.ajax({
                url: '<?php echo $urlval; ?>ajax/delete_room.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response === 'success') {
                        alert('Room deleted successfully.');
                        table.draw(); 
                    } else {
                        alert('Failed to delete room. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        }
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
                    tablename: document.getElementById('tablename'),
                    tablestatus: document.getElementById('tablestatus'),
                    tableaction: document.getElementById('tableaction'),
               
                    updateProfile:document.getElementById('update')
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
                allromm: document.getElementsByClassName('allromm'),
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'allromm') {
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

</body>
</html>
