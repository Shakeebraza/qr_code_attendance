<?php
include_once('header.php');
<<<<<<< HEAD
if (!isset($_SESSION['type']) || $_SESSION['type'] != ADMIN_USER_ID) {
=======
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID) {
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95
    header('Location: ../login.php');
    exit();
}
?>
<style>
#attendance-table thead th {
    border-bottom: 2px solid #dee2e6;
    text-align: center;
    padding: 10px;
}

#attendance-table thead th input {
    width: 100%;
    padding: 3px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}
td {
    text-align: center;
}
#attendance-table_length{
   display: flex;
   justify-content: start;
   align-items: center;
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
    }
    tr{
        text-align: center;
    }
    #filter-button:hover{
       color: white;
    }
      @media (max-width: 768px) {

        #attendance-table td:nth-child(1),
        #attendance-table th:nth-child(1) {
            display: none;
        }
        #attendance-table td:nth-child(4),
        #attendance-table th:nth-child(4) {
            display: none;
        }
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
                #export-button{
             font-size: 9px;
              margin-top: 11px;
            background-color: #198754;
            color: black;
            border: none;
            margin-bottom: 5px; 
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
    .modal-content {
        background-color: #000;
        color: #fff;
        border: 1px solid #ff0000;
    }

    .modal-title {
        color: #ff0000;
    }
    .buttonn{
     margin-top: 13px;
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>" id="homeatt">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $urlval?>admin" id="adminatt">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="filteratt">Filter Attendance</li>
                </ol>
            </nav>
            <div style="display:flex; justify-content: space-between;">
            <h6 class="mb-4 todayatt">All Attendance</h6>
             
         <div class="" id="clock">00:00:00 AM</div>
        
        </div>
            <div class="table-responsive">
<div class="filter-box p-3 border rounded shadow-sm">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="fromdate" class="form-label">From Date</label>
            <input type="date" placeholder="From Date" id="fromdate" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="todate" class="form-label">To Date</label>
            <input type="date" placeholder="To Date" id="todate" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="filter-name form-control" placeholder="Name" id="name">
        </div>
    </div>
    <div class="text-center">
        <button id="filter-button" class="btn btn-outline-info me-2">Filter</button>
        <button id="export-button" class="btn btn-outline-success">Export CSV</button>
    </div>
</div>
                <table id="attendance-table" class="table">
                    <thead>
                  
                          
                            
                                
                      
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" id="tablename">Name</th>
                            <th scope="col" id="tabledate">Date</th>
                            <th scope="col" id="tabletime">Time</th>
                            <th scope="col" id="tableroom">Room</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
        "ajax": {
            "url": "<?php echo $urlval ?>ajax/advancefliter.php",
            "type": "POST",
            "data": function(d) {
                d.name = $('#name').val();
                d.fromdate = $('#fromdate').val();
                d.todate = $('#todate').val();
                d.room = $('#room').val();
                d.order_column = d.columns[d.order[0].column].data;
            d.order_dir = d.order[0].dir;

            }
        },
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "date" },
            { "data": "time" },
            { "data": "room" }
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


    $('#filter-button').on('click', function() {
        table.ajax.reload(); 
    });

    
    $('#attendance-table tbody').on('click', 'tr', function() {
        var rowData = table.row(this).data();
        if (rowData) {
            $('#modalStudentID').text(rowData.id);
            $('#modalStudentName').text(rowData.username);
            $('#modalDate').text(rowData.date);
            $('#modalTime').text(rowData.time);
            $('#modalRoom').text(rowData.room);
            $('#rowDataModal').modal('show');
        }
    });
    $('.clsrowDataModal').on('click', function() {
        $('#rowDataModal').modal('hide');
    });
    
    
            function applyMobileStyles() {
        if ($(window).width() <= 768) {
            $('#attendance-table th, #attendance-table td').css({
                'padding': '2px',
                'font-size': '8px', 
                'width': '30px', 
                'text-align': 'center' 
            });

            $('#attendance-table').css({
                'table-layout': 'fixed', 
                'width': '100%' 
            });

            $('.dataTables_wrapper').css({
                'overflow-x': 'hidden'
            });
        } else {
            
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
});

let currentLanguage = 'es';

function loadLanguage(language) {
    fetch(`<?php echo $urlval ?>language/admin/${language}.json`)
        .then(response => response.json())
        .then(data => {
            console.log(data);

        
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
                tabledate: document.getElementById('tabledate'),
                tabletime: document.getElementById('tabletime'),
                tableroom: document.getElementById('tableroom'),
                previous: document.getElementById('attendance-table_previous'),
                next: document.getElementById('attendance-table_next'),
                filteratt:document.getElementById('filteratt'),
                tableemail:document.getElementById('tableemail'),
                fromdate:document.getElementById('fromdate'),
                todate:document.getElementById('todate')
            };


            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || '';
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            const elementsByClass = {
                todayattendance: document.getElementsByClassName('todayatt'),
                namePlaceholder: document.getElementsByClassName('filter-name'),
                // numberPlaceholder: document.getElementsByClassName('filter-number'),
                filterButtonText: document.getElementsByClassName('filter-button'),
                filterRoom: document.getElementsByClassName('filter-room')
            };
            
        
            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    if (key === 'todayattendance' || key === 'filterButtonText' || key === 'filterRoom') {
                        elements[i].innerText = data[key] || elements[i].innerText;
                    } else if (key === 'namePlaceholder' || key === 'emailPlaceholder') {
                        elements[i].placeholder = data[key] || elements[i].placeholder;
                    }
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

$('#export-button').on('click', function(e) {
            e.preventDefault();


            let fromDate = $('#fromdate').val();
            let toDate = $('#todate').val();
            let name = $('#name').val();

    
            $.ajax({
                url: '<?php echo $urlval?>export/exportatt.php', 
                type: 'POST',
                data: {
                    fromdate: fromDate,
                    todate: toDate,
                    name: name
                },
                xhrFields: {
                    responseType: 'blob' 
                },
                success: function(response, status, xhr) {
                    const url = window.URL.createObjectURL(new Blob([response]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'export.csv');
                    document.body.appendChild(link);
                    link.click(); 
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