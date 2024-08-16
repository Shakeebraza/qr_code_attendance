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
    }

    .modal-dialog {
        max-width: 500px;
        margin: 30px auto;
    }

    .modal-content {
        background-color: #212121;
        color: #ffffff;
        border-radius: 10px;
        border: 1px solid #333;
 
    }

    .modal-header {
        border-bottom: 1px solid #333;
        /* Dark border */
        background-color: #1c1c1c;
        /* Slightly darker header */
    }

    .modal-header .modal-title {
        color: #ffffff;
        /* White title color */
    }

    .modal-header .close {
        color: #ffffff;
        /* White close button */
        opacity: 0.5;
    }

    .modal-header .close:hover {
        opacity: 1;
    }

    .modal-body {
        padding: 20px;
        /* Add padding for body */
    }

    .form-control {
        background-color: #333;
        color: #ffffff;
        border: 1px solid #444;
    }

    .form-control::placeholder {
        color: #aaa;
    }

    .modal-footer {
        border-top: 1px solid #333;
        background-color: #1c1c1c;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    td {
        text-align: center;
    }

    .norwork {
        color: red;
    }

    /* Existing styles... */

    /* Media query to hide specific columns on mobile devices */
    @media only screen and (max-width: 768px) {

        /* Hide ID column */
        #attendance-table thead th:nth-child(1),
        #attendance-table tbody td:nth-child(1) {
            display: none;
        }

        /* Hide Email column */
        #attendance-table thead th:nth-child(3),
        #attendance-table tbody td:nth-child(3) {
            display: none;
        }

        /* Hide Date column */
        #attendance-table thead th:nth-child(4),
        #attendance-table tbody td:nth-child(4) {
            display: none;
        }
    }

    @media only screen and (max-width: 768px) {
        #attendance-table thead th input {
            width: 40%;
            /* Reduce the width for better mobile display */
            padding: 5px;
            /* Adjust padding if needed */
            font-size: 12px;
            /* Adjust font size for better readability on small screens */
        }

        #filter-button {
            padding: 5px 10px;
            /* Adjust button padding */
            font-size: 12px;
            /* Adjust font size for the button */
        }
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Attendance</li>
                </ol>
            </nav>

            <h6 class="mb-4">All Attendance</h6>



            <div class="table-responsive">
                <table id="attendance-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"><input type="text" placeholder="Name" id="name"></th>
                            <th scope="col"><input type="text" placeholder="Email" id="email"></th>
                            <th scope="col"><input type="date" placeholder="Date" id="date"></th>
                            <th scope="col"><input type="time" placeholder="Time" id="time"></th>
                            <th scope="col"></th>
                            <th scope="col"><button id="filter-button" class="btn btn-outline-info">Filter</button></th>
                            <th scope="col"></th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <?php 
                            if($_SESSION['type'] == 2){
                                echo '
                                <th scope="col">Name</th>
                                ';
                            }else{
                                echo '
                                <th scope="col">Work Name</th>
                                
                                ';
                            }
                            ?>
                            
                            <th scope="col">Number</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Work Type</th>
                            <th scope="col">Room</th>
                            <th scope="col">Action</th>
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
                <h5 class="modal-title">Assign Room</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="userId" value="">
                <input type="hidden" id="qrcode" value="">
                <label for="roomName">Select Rooms:</label>
                <select id="roomName" class="form-control" multiple aria-label="multiple select example">
                    <!--<option value="Room 1">Room 1</option>-->
                    <!--<option value="Room 2">Room 2</option>-->
                    <!--<option value="Room 3">Room 3</option>-->
                    <?php


                    if (isset($rooms['data']) && is_array($rooms['data'])) {
                        foreach ($rooms['data'] as $val) {
                            echo '
                            <option value="' . htmlspecialchars($val['name'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($val['name'], ENT_QUOTES, 'UTF-8') . '</option>
                            ';
                        }
                    } else {
                        echo '<option value="">No rooms available</option>';
                    }
                    ?>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveRoom">Save</button>
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
            "ajax": {
                "url": "<?php echo $urlval; ?>ajax/fetch_users_attendance.php",
                "type": "POST",
                "data": function(d) {
                    var currentDate = new Date().toISOString().split('T')[0];
                    d.name = $('#name').val();
                    d.email = $('#email').val();
                    d.date = $('#date').val() || currentDate;
                    d.time = $('#time').val();
                }
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "username"
                },
                {
                    "data": "email"
                },
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
            "createdRow": function(row, data, dataIndex) {
                // Add class to 'worktype' column if value is 'no_work'
                if (data.worktype === 'no_work') {
                    $(row).find('td:eq(5)').addClass('norwork');
                }
            }
        });

        // Reload table data on filter button click
        $('#filter-button').on('click', function() {
            table.ajax.reload();
        });


        $(document).on('click', '.open-popup', function() {
            var userId = $(this).data('id');
            $('#userId').val(userId);
            $('#roomPopup').show();
        });

        $('#saveRoom').on('click', function() {
            var roomNames = $('#roomName').val();
            var userId = $('#userId').val();

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
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function() {
                        alert('There was an error saving the room assignment.');
                    }
                });
            } else {
                alert('Please select at least one room.');
            }
        });


        $('.close, .btn-secondary').on('click', function() {
            $('#roomPopup').hide();
        });
    });
    $(function() {
        $("#example").multiselect();
    });
</script>



</body>

</html>