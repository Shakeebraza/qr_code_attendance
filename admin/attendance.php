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
            <th scope="col"><button id="filter-button" class="btn btn-outline-info m-2">Filter</button></th>
        </tr>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Date</th>
            <th scope="col">Time</th>
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
                d.name = $('#name').val();
                d.email = $('#email').val();
                d.date = $('#date').val();
                d.time = $('#time').val();
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "email" },
            { "data": "date" },
            { "data": "time" },
            { "data": "action", "sortable": false }
        ]
    });

    // Add event listener for the filter button
    $('#filter-button').on('click', function() {
        table.ajax.reload(); // Reload the table data based on current filter values
    });
});



</script>



</body>

</html>