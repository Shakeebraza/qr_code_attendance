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
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Rooms</li>
                </ol>
            </nav>

            <h6 class="mb-4">All Rooms</h6>

            <div class="table-responsive">
                <table id="rooms-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Enabled</th>
                            <th scope="col">Action</th>
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
        ]
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
</script>

</body>
</html>
