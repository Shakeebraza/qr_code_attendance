<?php
include_once('header.php');
$data=$funObject->GetAttendance();
$users=$funObject->GetAllUser();

?>
<style>
    #users-table_length{
   display: flex;
   justify-content: start;
   align-items: center;
}
input.form-control, select.form-control {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ced4da;
    transition: box-shadow 0.3s ease-in-out, border-color 0.3s ease-in-out;
}

/* On focus, add a slight shadow and color change */
input.form-control:focus, select.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
    outline: none;
}

/* Add hover effect to select options */
select.form-control:hover, input.form-control:hover {
    background-color: #f1f1f1;
}

/* Button styles */
.btn-info {
    padding: 8px 16px;
    background-color: #17a2b8;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease-in-out;
}

.btn-info:hover {
    background-color: #138496;
}

/* Table header style */
th {
    text-align: center;
    padding: 10px;
}

/* Optional: Adjust table row background and spacing */
tr {
    /*background-color: #f8f9fa;*/
    padding: 10px;
}

th {
    vertical-align: middle;
}
</style>

<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>admin" id="adminatt">Admin</a></li>
                    <li class="breadcrumb-item active alluseratt" aria-current="page" >All Users</li>
                </ol>
            </nav>

            <h6 class="mb-4 alluseratt">All Users</h6>

        

            <div class="table-responsive">
            <table id="users-table" class="table">
                        <thead>
                            <tr style="background-color: #33394836;">
                                <th></th>
                            
                
                                <th scope="col">
                                    <select id="user-type" class="filter-user-type form-control">
                                        <option value="0">User</option>
                                        <option value="1">Supervisor</option>
                                    </select>
                                </th>

                                <th scope="col">
                                    <select id="status" class="filter-status form-control">
                                        <option value="">Select Status</option>
                                        <option value="1">Verified</option>
                                        <option value="0">Unverified</option>
                                    </select>
                                </th>
                                <th></th>
                            
                                
                            </tr>
                            <tr>
                            
                                <th scope="col" id="tableuser">User</th>
                 
                                <th scope="col" id="tabletype">Type</th>
                            
                                <th scope="col" id="tablestatus">Status</th>
                                <th scope="col" id="tableaction">Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
    var table = $('#users-table').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "pageLength": 50,
        "ajax": {
            "url": "<?php echo $urlval; ?>ajax/fetch_users.php",
            "type": "POST",
            "data": function(d) {
                            // Fetch values from the dropdowns for filtering
                            d.user_type = $('#user-type').val(); // Get user type from dropdown
                d.status = $('#status').val();       // Get status from dropdown
                d.search.value = $('#search').val();
                        }
        },
        "columns": [
           
            { "data": "username" },
        
            { "data": "type" },
          
            { "data": "verified" },
            { "data": "action", "sortable": false } 
        ],
        "order": [],
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

    $('form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });
        $('#user-type, #status').on('change', function() {
        table.draw(); // Redraw the table with new filter values
    });
    $('#users-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        alert('Edit button clicked for ID: ' + id);
    });

    $('#users-table').on('click', '.delete-btn', function() {
        var id = $(this).data('id');

        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: '<?php echo $urlval; ?>ajax/delete_user.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response === 'success') {
                        alert('User deleted successfully.');
                        table.draw(); 
                    } else {
                        alert('Failed to delete user. Please try again.');
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
                admin: document.getElementById('adminatt'),
                tablename: document.getElementById('tablename'),
                Users:document.getElementById('tableuser'),
                arrivalLabel:document.getElementById('arrivalLabel'),
                tabledate: document.getElementById('tabledate'),
                qrlink: document.getElementById('tableqelink'),
                previous: document.getElementById('attendance-table_previous'),
                next: document.getElementById('attendance-table_next'),
                tablestatus:document.getElementById('tablestatus'),
                tableaction:document.getElementById('tableaction'),
                type:document.getElementById('tabletype')
            };

            // Update text for elements by ID
            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || element.key;
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            const elementsByClass = {
                alluseratt: document.getElementsByClassName('alluseratt'),
                filterButtonText: document.getElementsByClassName('filter-button'),
                viewqrcode: document.getElementsByClassName('viewqrcode') // Using 'viewqrcode' as the class name
            };
            
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'alluseratt' || key === 'filterButtonText') {
                        elements[i].innerText = data[key] || elements[i].innerText;
                    } else if (key === 'viewqrcode') {
                        elements[i].value = data[key] || elements[i].value; 
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