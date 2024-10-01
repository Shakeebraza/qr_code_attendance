<?php
include_once('header.php');

$rooms = json_decode($funObject->GetRooms(), true);



?>

<style>
    #attendance-table thead th {
        border-bottom: 2px solid #dee2e6;
        text-align: center;
        padding: 10px;
    }


    #attendance-table thead th input {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }

    .modal {
    background-color: rgba(0, 0, 0, 0.5); 
    color: #fff; 
}

.modal-dialog {
    max-width: 500px;
    margin: 1.75rem auto;
}

.modal-content {
    background-color: #000; 
    color: #fff;
    border-radius: 0.3rem;
    padding: 1rem;
}

.modal-header {
    border-bottom: 1px solid #444;
    padding-bottom: 0.5rem;
}

.modal-title {
    color: #EB1616;
}

.close {
    color: #fff;
    opacity: 0.5;
}

.close:hover {
    color: #e74c3c; 
    opacity: 1;
}


.search-container {
    margin-top: 1rem;
}

.search-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #444;
    border-radius: 0.3rem;
    background-color: #222; 
    color: #fff; 
}

.search-input::placeholder {
    color: #aaa; 
}

.suggestions-list {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: #222; 
    border: 1px solid #444;
    border-radius: 0.3rem;
    position: absolute;
    width: 94%;
    max-height: 200px;
    overflow-y: auto;
}

.suggestions-list li {
    padding: 0.5rem;
    cursor: pointer;
}

.suggestions-list li:hover {
    background-color: #444;
}

.tags-container {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.tag {
    background-color: #333;
    color: #fff;
    border-radius: 0.3rem;
    padding: 0.5rem;
    margin: 0.2rem;
    display: flex;
    align-items: center;
}

.tag .remove-icon {
    cursor: pointer;
    margin-left: 0.5rem;
    color: #fff;
}

.tag .remove-icon:hover {
    color: #ddd; 
}


.modal-footer {
    border-top: 1px solid #444;
    padding-top: 0.5rem;
}

.btn-primary {
    background-color: #EB1616; 
    border-color: #e74c3c;
}

.btn-primary:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

.btn-secondary {
    background-color: #333; 
    border-color: #333;
}

.btn-secondary:hover {
    background-color: #444;
    border-color: #444;
}

#attendance-table_length{
    display: flex;
    justify-content: start;
    align-items: center;
}
   

    @media only screen and (max-width: 768px) {
        #attendance-table thead th input {
            padding: 5px;
        }

        #filter-button {
            padding: 5px 10px;
            font-size: 15px;
           
        }
    }
    tr{
        text-align: center;
    }
    #filter-button:hover{
       color: white;
    }
    #attendance-table thead tr th input{
        outline: none;
    }
    #attendance-table thead tr{
        text-wrap: nowrap;   
    }
    #attendance-table thead{
        text-align: center;
        padding: 10px;
        border-bottom: 2px solid #dee2e6;
        border-top: 0px;
    }
    .table.dataTable tbody td{
        text-wrap: nowrap;
    }
  
  
 @media (max-width: 768px) {
        /* Hide ID column 
        /* Hide Work Type column */
        #attendance-table td:nth-child(1),
        #attendance-table th:nth-child(1) {
            display: none;
        }
        .table.dataTable tbody td{
        text-wrap: wrap;
    }
        #attendance-table td:nth-child(3),
        #attendance-table th:nth-child(3) {
            display: none;
        }
        /* Hide Arrival Time column */
        #attendance-table td:nth-child(4),
        #attendance-table th:nth-child(4) {
            display: none;
        }
        #attendance-table td{
            font-size:7px;
           
        }
    }
        @media only screen and (max-width: 768px) {
        #attendance-table thead th input {

            width: 100%;
            
              box-sizing: border-box;
              border: 1px solid #ced4da;
              border-radius: 4px;
             font-size: 8px;
              margin-bottom: 5px;
        }
        #{
            width:20px;
        }

        #filter-button {
        font-size: 9px;
            margin-top: 11px;
            background-color: #0dcaf0;
            color: black;
            border: none;
            margin-bottom: 5px;
           
        }
                .main-search{
        display:flex;
        gap:5px;
    }
    }
     @media (max-width: 576px) {
        #rowDataModal .modal-dialog {
            width: 90%;
            margin: auto;
        }
          #rowDataModal .modal-dialog {
        max-width: 70%;
    }
        .main-search{
        display:flex;
        gap:5px;
    }
    }

    /* Style adjustments for mobile */
    #rowDataModal .modal-dialog {
        max-width: 50%;
    }

    #rowDataModal .modal-header, #rowDataModal .modal-footer {
        border-color: #ff0000;
    }

    #rowDataModal .modal-body p {
        margin: 5px 0;
        font-size: 16px;
    }

    /* Button styling */
    .btn-secondary {
        background-color: #ff0000;
        border: none;
        color: #fff;
    }

    .btn-secondary:hover {
        background-color: #cc0000;
    }

    /* Close button */
    .close {
        color: #fff;
        opacity: 0.8;
    }

    .close:hover {
        color: #ff0000;
        opacity: 1;
    }

    /* Modal content styling */
    .modal-content {
        background-color: #000;
        color: #fff;
        border: 1px solid #ff0000;
    }

    .modal-title {
        color: #ff0000;
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
                    <li class="breadcrumb-item todayatt active" aria-current="page" >Today Attendance</li>
                </ol>
            </nav>
            <div style="display:flex; justify-content: space-between;">
            <h6 class="mb-4 todayatt">All Attendance</h6>

            
             <div class="" id="clock">00:00:00 AM</div>
            
            </div>
            <div class="table-responsive">
           <div class="filter-box p-3 border rounded shadow-sm">
        <div class="row mb-3 main-search">
            <div class="col-md-4">
                <input type="text" placeholder="Search by Name..." class="filter-name form-control" id="name">
            </div>
            <div class="col-md-4">
                <input type="date" class="filter-date form-control" id="date" style="cursor: pointer;">
            </div>
            <div class="col-md-4">
                <select id="worktype" class="form-control" style="background-color:black;">
                    <option value="">Seleccionar tipo de trabajo</option>
                    <option value="workNoOuting">Trabajo (sin salida)</option>
                    <option value="workWithOuting">Trabajo (con salida)</option>
                    <option value="stayingclose">Quedarse cerca (sin salida)</option>
                    <option value="Stayclosewithexit">Quedarse cerca (con salida)</option>
                    <option value="noWork">Sin trabajo</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
           <div class="col-md-4">
            <label for="roomnew">Sala</label>
            <select id="roomnew" class="form-control" style="background-color:black;">
                <option value="">Seleccione una sala</option>
                <?php 
                $rooms = json_decode($funObject->GetRooms(), true);;
                if (!empty($rooms['data'])) {
                    foreach ($rooms['data'] as $room) {
                       echo '<option value="' . htmlspecialchars($room['name']) . '">' . htmlspecialchars($room['name']) . '</option>';

                    }
                } else {
                    echo '<option value="">No hay salas disponibles</option>';
                }
                ?>
            </select>
        </div>
            <div class="col-md-4 d-flex align-items-end">
                <button id="filter-button" class="btn btn-outline-info filter-button">Filter</button>
            </div>
        </div>
    </div>
        

                <table id="attendance-table" class="table">
                    <thead>



                        <tr>
                            <th scope="col" id="tableid">id</th>
                            <?php 
                            if($_SESSION['type'] == 2){
                                echo '
                                <th scope="col" id="tablename">Name</th>
                                ';
                            }else{
                                echo '
                                <th scope="col" id="tableworkname">Work Name</th>
                                
                                ';
                            }
                            ?>
                            
                            <!--<th scope="col" id="tablenumber">Number</th>-->
                            <th scope="col" id="tabledate">Date</th>
                            <th scope="col" id="tabletime">Time</th>
                            <th scope="col" id="tableworktype">Work Type</th>
                            <th scope="col" id="tableroom">Room</th>
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
<div id="roomPopup" class="modal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignroomm">Assign Room</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <input type="hidden" id="userId" value="">
            <input type="hidden" id="qrcode" value="">
            <label for="roomName" id="selectrooms">Select Rooms:</label>

            <div class="search-container">
                <div class="tags-container" id="tagsContainer">
            
                </div>
                <input
                    type="search"
                    id="searchInput"
                    class="search-input"
                    placeholder="Search..."
                />
                <ul id="suggestionsList" class="suggestions-list">
               
                </ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveRoom">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="clsbtn">Close</button>
            </div>
        </div>
    </div>
</div>


<div id="rowDataModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Row Details</h5>
                <button type="button" class="close clsrowDataModal" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>identificación:</strong> <span id="modalStudentID"></span></p>
                <p><strong>Nombre:</strong> <span id="modalStudentName"></span></p>
                <p><strong>Fecha:</strong> <span id="modalDate"></span></p>
                <p><strong>Tiempo:</strong> <span id="modalTime"></span></p>
                <p><strong>Tipo de trabajo:</strong> <span id="modalworkrtype"></span></p>
                <p><strong>Sala:</strong> <span id="modalRoom"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary clsrowDataModal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>

<script>



    $(document).ready(function() {

        var table = $('#attendance-table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "pageLength": 50,
            "ajax": {
                "url": "<?php echo $urlval; ?>ajax/fetch_users_attendance.php",
                "type": "POST",
                "data": function(d) {
                    var currentDate = new Date().toISOString().split('T')[0];
                    d.name = $('#name').val();
                    d.email = $('#email').val();
                    d.date = $('#date').val();
                    d.time = $('#time').val();
                    d.worktype = $('#worktype').val();
                    d.room = $('#roomnew').val();
                    d.order_column = d.columns[d.order[0].column].data;
                    d.order_dir = d.order[0].dir;
                }
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "username"
                },
                // {
                //     "data": "email"
                // },
                 {
                    "data": "date"
                },
                {
                    "data": "time"
                },
                {
                    "data": "worktype"
                },
                {
                    "data": "room"
                },
                {
                    "data": "action",
                    "sortable": false
                }
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
            "createdRow": function(row, data, dataIndex) {
             
                if (data.worktype === 'no_work') {
                    $(row).find('td:eq(5)').addClass('norwork');
                }
            }
        });

    
        $('#filter-button').on('click', function() {
            table.ajax.reload();
        });

$('#attendance-table tbody').on('click', 'tr', function(event) {
        // Prevent modal from opening when an action button is clicked
        if ($(event.target).closest('.btn').length) {
            return; // Exit if the click happened on a button
        }

        var rowData = table.row(this).data();
        if (rowData) {
            $('#modalStudentID').text(rowData.id);
            $('#modalStudentName').text(rowData.username);
            $('#modalDate').text(rowData.date);
            $('#modalTime').text(rowData.time);
            $('#modalworkrtype').text(rowData.worktype);
            $('#modalRoom').text(rowData.room);
            $('#rowDataModal').modal('show');
        }
    });

    // Close the modal
    $('.clsrowDataModal').on('click', function() {
        $('#rowDataModal').modal('hide');
    });
        // $(document).on('click', '.open-popup', function() {
        //     var userId = $(this).data('id');
        //     $('#userId').val(userId);
        //     $('#roomPopup').show();
        // });
        $(document).on('click', '.open-popup', function() {
    var userId = $(this).data('id');
    $('#userId').val(userId);
    $('#roomPopup').show();

    // Trigger AJAX request to fetch assigned rooms
    $.ajax({
        url: "<?php echo $urlval; ?>ajax/getAssignedRooms.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            userId: userId
        }),
        success: function(response) {
            var result = JSON.parse(response);
            $('#tagsContainer').empty(); // Clear previous tags

            if (result.assignedRooms != '' && result.assignedRooms.length > 0) {
                // Handle the assigned rooms, e.g., display them in the popup
                result.assignedRooms.forEach(function(room) {
                    // Add each room as a tag
                    var tag = $('<div class="tag" data-text="' + room + '">' + room + ' <span class="remove-icon">&times;</span></div>');
                    $('#tagsContainer').append(tag);
                });
            } else {
                
            }
        },
        error: function() {
            alert('An error occurred while retrieving room assignments.');
        }
    });
});

// Delegate click event to handle removing tags
$(document).on('click', '.remove-icon', function() {
    $(this).closest('.tag').remove();
});

            $('#saveRoom').on('click', function() {
        var roomNames = [];
        $('#tagsContainer .tag').each(function() {
            roomNames.push($(this).data('text'));
        });

        var userId = $('#userId').val();
        var attendanceId = $('#userId').val(); // Assuming you have a hidden input field for attendanceId

        if (roomNames.length > 0) {
            var roomNamesStr = roomNames.join(',');

            $.ajax({
                url: "<?php echo $urlval; ?>auth/addroom.php",
                type: "POST",
                data: {
                    userId: userId,
                    roomName: roomNamesStr
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert(result.message);
                        $('#roomPopup').hide();
                        location.reload();
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert('There was an error saving the room assignment.');
                }
            });
        } else {
            $.ajax({
                url: "<?php echo $urlval; ?>ajax/remove_room.php", 
                type: 'POST',
                data: {
                    tbl_attendance_id: attendanceId
                },
                dataType: 'json', 
                success: function(response) {
                    if (response.success) {
                        alert('La habitación se eliminó correctamente.');
                        window.location.reload(); 
                    } else {
                        alert('No se pudo eliminar el espacio.');
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        }
    });


        $('.close, .btn-secondary').on('click', function() {
            $('#roomPopup').hide();
        });
        
        
        
         $(document).on('click', '.removeromm', function() {
        var attendanceId = $(this).data('id');
        var button = $(this);

    $(document).on('click', '.removeromm', function(event) {
        event.preventDefault(); 

        var attendanceId = $(this).data('id');
        var button = $(this);

        $.ajax({
            url: "<?php echo $urlval; ?>ajax/remove_room.php", 
            type: 'POST',
            data: {
                tbl_attendance_id: attendanceId
            },
            dataType: 'json', 
            success: function(response) {
                if (response.success) {
                
                    alert('La habitación se eliminó correctamente.');
                    window.location.reload(); 
                } else {
                    alert('No se pudo eliminar el espacio.');
                }
            },
            error: function() {
                alert('An error occurred while processing your request.');
            }
        });
    });
    });
    });
    $(function() {
        $("#example").multiselect();
    });



    document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("searchInput");
    const suggestionsList = document.getElementById("suggestionsList");
    const tagsContainer = document.getElementById("tagsContainer");
    const saveRoomButton = document.getElementById("saveRoom");
    const userIdInput = document.getElementById("userId");
    const qrcodeInput = document.getElementById("qrcode");

    const response = <?php echo json_encode($rooms); ?>;
    const suggestions = response.data.map(room => room.name);

    function filterSuggestions(query) {
        return suggestions.filter(suggestion =>
            suggestion.toLowerCase().includes(query.toLowerCase())
        );
    }

    function renderSuggestions(filteredSuggestions) {
        suggestionsList.innerHTML = "";
        if (filteredSuggestions.length > 0) {
            filteredSuggestions.forEach(suggestion => {
                const li = document.createElement("li");
                li.textContent = suggestion;
                li.addEventListener("click", () => {
                    addTag(suggestion);
                    input.value = "";
                    suggestionsList.innerHTML = "";
                });
                suggestionsList.appendChild(li);
            });
        } else {
            const li = document.createElement("li");
            li.textContent = "Habitación no encontrada";
            li.className = "no-suggestions";
            suggestionsList.appendChild(li);
        }
    }

    function addTag(tagText) {
        const tag = document.createElement("div");
        tag.className = "tag";
        tag.dataset.text = tagText;
        tag.innerHTML = `${tagText} <span class="remove-icon">&times;</span>`;
        tag.querySelector(".remove-icon").addEventListener("click", () => {
            tagsContainer.removeChild(tag);
        });
        tagsContainer.appendChild(tag);
    }

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    input.addEventListener("input", debounce(() => {
        const query = input.value;
        if (query) {
            const filteredSuggestions = filterSuggestions(query);
            renderSuggestions(filteredSuggestions);
        } else {
            suggestionsList.innerHTML = "";
        }
    }, 300));

    document.addEventListener("click", (event) => {
        if (
            !input.contains(event.target) &&
            !suggestionsList.contains(event.target)
        ) {
            suggestionsList.innerHTML = "";
        }
    });

});


let currentLanguage = 'es';

function loadLanguage(language) {
    fetch(`<?php echo $urlval ?>language/admin/${language}.json`)
        .then(response => response.json())
        .then(data => {
            console.log();

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
                tableid: document.getElementById('tableid'),
                tablename: document.getElementById('tablename'),
                tableworkname: document.getElementById('tableworkname'),
                tablenumber: document.getElementById('tablenumber'),
                tabledate: document.getElementById('tabledate'),
                tabletime: document.getElementById('tabletime'),
                tableworktype: document.getElementById('tableworktype'),
                tableroom: document.getElementById('tableroom'),
                tableaction: document.getElementById('tableaction'),
                previous: document.getElementById('attendance-table_previous'),
                next: document.getElementById('attendance-table_next'),
                assignroom:document.getElementById('assignroomm'),
                selectrooms:document.getElementById('selectrooms'),
                savebtn:document.getElementById('saveRoom'),
                closevtn:document.getElementById('clsbtn'),
            };

            // Update text for elements by ID
            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || '';
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            // Update text for elements by class name
            const elementsByClass = {
                todayattendance: document.getElementsByClassName('todayatt'),
                namePlaceholder: document.getElementsByClassName('filter-name'),
                emailPlaceholder: document.getElementsByClassName('filter-email'),
                filterButtonText: document.getElementsByClassName('filter-button')
            };

            // Update text for each class name (iterate through all elements of the class)
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'todayattendance') {
                        elements[i].innerText = data[key] || '';
                    } else if (key === 'namePlaceholder') {
                        elements[i].placeholder = data['namePlaceholder'] || 'Name';
                    } else if (key === 'emailPlaceholder') {
                        elements[i].placeholder = data['emailPlaceholder'] || 'Email';
                    } else if (key === 'filterButtonText') {
                        elements[i].innerText = data['filterButtonText'] || 'Filter';
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


function applyMobileStyles() {
    if ($(window).width() <= 768) {
        // Apply mobile-specific styles
        $('#attendance-table th, #attendance-table td').css({
            'padding': '2px', // Reduce padding for compact table
            'font-size': '8px', // Reduce font size
            'width': '30px', // Set the width to 30px for all table headers and cells
            'text-align': 'center' // Center the text
        });

        // Adjust button styles for mobile view
        $('.btn.btn-outline-info').css({
            'font-size': '10px',
            'padding': '4px'
        });

        // Specifically target the 'Room' button and apply styles
        $('.btn.btn-outline-warning').css({
            'font-size': '8px',  // Set font size to 8px
            'padding': '2px'     // Set padding to 2px
        });

        // Set table layout for mobile view
        $('#attendance-table').css({
            'table-layout': 'fixed', // Ensure fixed table layout to fit content
            'width': '100%' // Ensure full width usage
        });

        // Allow horizontal scroll for table in mobile view
        $('.dataTables_wrapper').css({
            'overflow-x': 'auto' // Enable horizontal scroll
        });
    } else {
        // Revert to default styles for larger screens
        $('#attendance-table th, #attendance-table td').css({
            'padding': '', 
            'font-size': '', 
            'width': '', 
            'text-align': ''
        });

        $('#attendance-table').css({
            'table-layout': '',
            'width': ''
        });

        // Revert to default overflow settings
        $('.dataTables_wrapper').css({
            'overflow-x': ''
        });
    }
}

// Apply mobile styles on page load and on window resize
applyMobileStyles();
$(window).resize(function() {
    applyMobileStyles();
});

</script>

<script src="<?php echo $urlval ?>admin/js/clock.js"></script>

</body>

</html>