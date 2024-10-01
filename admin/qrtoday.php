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
           #attendance-table td:nth-child(5),
        #attendance-table th:nth-child(5) {
            display: none;
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
        #csv-button{
             font-size: 9px;
              margin-top: 11px;
            background-color: #198754;
            color: black;
            border: none;
            margin-bottom: 5px; 
        }
        .row.mb-3 {
  display: flex;
  gap: 5px;
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
        #attendance-table td:nth-child(5),
        #attendance-table th:nth-child(5) {
            display: none;
        }
        /* Hide Arrival Time column */
        #attendance-table td:nth-child(5),
        #attendance-table th:nth-child(5) {
            display: none;
        }
        #attendance-table td{
            font-size:7px;
           
        }
        .row.mb-3 {
  display: flex;
  gap: 5px;
}
    }

   
</style>
<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>admin" id="homeatt">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="qratt">Qr Attendance</li>
                </ol>
            </nav>

            

            <div style="display:flex; justify-content: space-between;">
            <h6 class="mb-4">Qr Attendance</h6>
                 
             <div class="" id="clock">00:00:00 AM</div>
            
            </div>
            <div class="table-responsive">
                <div class="container">
    <div class="row mb-3">
        <!-- Date filter -->
        <div class="col-md-4">
            <input type="date" placeholder="Date" id="date" class="form-control">
        </div>

        <!-- Name filter -->
        <div class="col-md-4">
            <input type="text" placeholder="Nombre" id="name" class="form-control">
        </div>

        <!-- Worktype selector -->
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

    <!-- Buttons section centered -->
    <div class="row">
        <div class="col-md-12 text-center">
            <button id="filter-button" class="btn btn-outline-info mx-2">Filter</button>
            <button id="csv-button" type="button" class="btn btn-outline-success mx-2">Export Csv</button>
        </div>
    </div>
</div>

    <table id="attendance-table" class="table">
        <thead>

            </tr>
            <tr>
                <th scope="col">#</th>
                <th scope="col" id="tablename">Name</th>
                <th scope="col" id="tableworktype">Work Type</th>
                <th scope="col" id="arrivalLabel">Arrival Time</th>
                <!--<th scope="col" id="tabledate">Date</th>-->
                <th scope="col" id="tableqelink">QR Code Link</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated here by DataTables -->
        </tbody>
    </table>
</div>
        </div>
    </div>
</div>

<div id="qrCodeModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="qrCodeImage" src="" alt="QR Code" width="300">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<div id="rowDataModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Row Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Identificación:</strong> <span id="modalStudentID"></span></p>
                <p><strong>Nombre:</strong> <span id="modalStudentName"></span></p>
                <p><strong>Tipo de trabajo:</strong> <span id="modalWorkType"></span></p>
                <p><strong>Hora de llegada:</strong> <span id="modalArrivalTime"></span></p>
                <!--<p><strong>URL del código QR:</strong> <span id="modalQRCodeURL"></span></p>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        "responsive": true,
        "ajax": {
            "url": "<?php echo $urlval; ?>ajax/fetch_qrcode.php",
            "type": "POST",
            "data": function(d) {
                d.date = $('#date').val();
                d.name = $('#name').val();
                d.worktype = $('#worktype').val();
            },
            "dataSrc": function(json) {
                return json.data;
            }
        },
        "columns": [
            { "data": "tbl_student_id" },
            { "data": "student_name" },
            { "data": "worktype" },
            { "data": "arrival_time" },
            { 
                "data": "generated_code_url",
                "render": function(data, type, row, meta) {
                    return '<a href="#" class="view-qrcode" data-url="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data='+data+'">Ver código QR</a>';
                }
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
        }
    });


    $('#attendance-table tbody').on('click', 'tr', function (e) {

        if ($(e.target).hasClass('view-qrcode')) {
            return;
        }
        
        var rowData = table.row(this).data();

        if (rowData) {

            $('#modalStudentID').text(rowData.tbl_student_id);
            $('#modalStudentName').text(rowData.student_name);
            $('#modalWorkType').text(rowData.worktype);
            $('#modalArrivalTime').text(rowData.arrival_time);
            // $('#modalQRCodeURL').text(rowData.generated_code_url);

            $('#rowDataModal').modal('show');
        }
    });

    $('#attendance-table').on('click', '.view-qrcode', function(e) {
        e.preventDefault();
        e.stopPropagation(); 

        var qrCodeUrl = $(this).data('url'); 
        $('#qrCodeImage').attr('src', qrCodeUrl); // Set QR code image source
        $('#qrCodeModal').modal('show'); // Show the modal
    });

    // Filter button reloads the table with the selected date
    $('#filter-button').on('click', function() {
        table.ajax.reload(); // Reload DataTable with new date parameter
    });

    // QR Code modal close
    $('#qrCodeModal').on('click', function () {
        $('#qrCodeImage').attr('src', ''); // Clear QR code image source when modal is closed
        $('#qrCodeModal').modal('hide');
    });
    
        $('#rowDataModal').on('click', function () {
        $('#rowDataModal').modal('hide'); // Close rowDataModal when needed
    });
    
    

});



let currentLanguage = 'es';

function loadLanguage(language) {
    fetch(`<?php echo $urlval ?>language/admin/${language}.json`)
        .then(response => response.json())
        .then(data => {
            // console.log(data);

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
                tableworktype:document.getElementById('tableworktype'),
                arrivalLabel:document.getElementById('arrivalLabel'),
                tabledate: document.getElementById('tabledate'),
                qrlink: document.getElementById('tableqelink'),
                previous: document.getElementById('attendance-table_previous'),
                next: document.getElementById('attendance-table_next'),
                filteratt:document.getElementById('filteratt'),
                tableemail:document.getElementById('tableemail'),
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
                todayattendance: document.getElementsByClassName('todayatt'),
                filterButtonText: document.getElementsByClassName('filter-button'),
                viewqrcode: document.getElementsByClassName('viewqrcode') // Using 'viewqrcode' as the class name
            };
            
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'todayattendance' || key === 'filterButtonText') {
                        elements[i].innerText = data[key] || elements[i].innerText;
                    } else if (key === 'viewqrcode') {
                        elements[i].value = data[key] || elements[i].value; // Updating 'value' instead of 'innerText'
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
                'width': '30px', // Set the width to 20px for all table headers and cells
                'text-align': 'center' // Center the text
            });

            $('#attendance-table').css({
                'table-layout': 'fixed', // Ensure fixed table layout to fit content
                'width': '100%' // Ensure full width usage
            });

            $('.dataTables_wrapper').css({
                'overflow-x': 'hidden' // Disable horizontal scroll
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

            $('.dataTables_wrapper').css({
                'overflow-x': ''
            });
        }
    }

   
    applyMobileStyles();
    $(window).resize(function() {
        applyMobileStyles();
    });
$('#csv-button').on('click', function(e) {
    e.preventDefault();

    // Get filter values
    let fromDate = $('#fromdate').val(); 
    let toDate = $('#date').val(); 
    let name = $('#name').val();
    let worktype = $('#worktype').val();

  
    $.ajax({
        url: '<?php echo $urlval?>export/exportqr.php',
        type: 'POST',
        data: {
            todate: toDate,
            name: name,
            worktype: worktype 
        },
        xhrFields: {
            responseType: 'blob' 
        },
        success: function(response, status, xhr) {
            // Create a link to download the CSV file
            const url = window.URL.createObjectURL(new Blob([response]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'export.csv'); 
            document.body.appendChild(link);
            link.click(); // Trigger download
            document.body.removeChild(link); 
        },
        error: function(xhr, status, error) {
            console.error('Error exporting CSV:', error);
        }
    });
});
</script>


    <script src="<?php echo $urlval ?>admin/js/clock.js"></script>
</body>

</html> 