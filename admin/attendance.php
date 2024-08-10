<?php
include_once('header.php');

?>
<STYle>
    /* Style the table header */
#attendance-table thead th {
    border-bottom: 2px solid #dee2e6;
    text-align: center;
    padding: 10px;
}

/* Style the input fields */
#attendance-table thead th input {
    width: 100%;
    padding: 5px;
    box-sizing: border-box; /* Ensures padding doesn't overflow */
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}
.modal {
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
}

.modal-dialog {
    max-width: 500px; /* Adjust width as needed */
    margin: 30px auto; /* Center modal vertically */
}

.modal-content {
    background-color: #212121; /* Dark background for modal content */
    color: #ffffff; /* White text color */
    border-radius: 10px; /* Rounded corners */
    border: 1px solid #333; /* Dark border */
}

.modal-header {
    border-bottom: 1px solid #333; /* Dark border */
    background-color: #1c1c1c; /* Slightly darker header */
}

.modal-header .modal-title {
    color: #ffffff; /* White title color */
}

.modal-header .close {
    color: #ffffff; /* White close button */
    opacity: 0.5;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body {
    padding: 20px; /* Add padding for body */
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

</STYle>
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
        </tr>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Number</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
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
                <input type="text" id="roomName" class="form-control" placeholder="Enter Room Name">
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
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "email" },
            { "data": "date" },
            { "data": "time" },
            { "data": "room" },
            { "data": "action", "sortable": false }
        ]
    });

   
    $('#filter-button').on('click', function() {
        table.ajax.reload(); 
    });

    $(document).on('click', '.open-popup', function() {
        var userId = $(this).data('id');
        
        $('#userId').val(userId); 
        $('#roomPopup').show();
    });

    $('#saveRoom').on('click', function() {
    var roomName = $('#roomName').val();
    var userId = $('#userId').val(); 

    
    if (roomName.trim() !== '') {
        $.ajax({
            url: "<?php echo $urlval; ?>auth/addroom.php",
            type: "POST",
            data: { userId: userId, roomName: roomName},
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message);
                    $('#roomPopup').hide();
                } else {
                    alert(result.message);
                }
            }
        });
    } else {
        alert('Please enter a room name');
    }
});

    // Handle the modal close event
    $('.close, .btn-secondary').on('click', function() {
        $('#roomPopup').hide();
    });
});




</script>



</body>

</html>