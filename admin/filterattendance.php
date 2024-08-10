<?php
include_once('header.php');

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
td {
    text-align: center;
}

</style>
<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
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
                            <th scope="col"><button id="filter-button" class="btn btn-outline-info">Filter</button></th>
                            <th scope="col"><input type="text" placeholder="Name" id="name"></th>
                            <th scope="col"><input type="text" placeholder="Email" id="email"></th>
                            <th scope="col">From Date<input type="date" placeholder="From Date" id="fromdate"></th>
                            <th scope="col">To Date<input type="date" placeholder="To Date" id="todate"></th>
                            <th scope="col"><input type="text" placeholder="Room" id="room"></th>
                        </tr>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Room</th>
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
    var table = $('#attendance-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "../ajax/advancefliter.php",
            "type": "POST",
            "data": function(d) {
                d.name = $('#name').val();
                d.email = $('#email').val();
                d.fromdate = $('#fromdate').val();
                d.todate = $('#todate').val();
                d.room = $('#room').val();
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "email" },
            { "data": "date" },
            { "data": "time" },
            { "data": "room" },
        ]
    });

    $('#filter-button').on('click', function() {
        table.ajax.reload(); 
    });
});




</script>



</body>

</html>