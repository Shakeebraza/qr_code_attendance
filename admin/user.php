<?php
include_once('header.php');
$data=$funObject->GetAttendance();
$users=$funObject->GetAllUser();

?>

<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Users</li>
                </ol>
            </nav>

            <h6 class="mb-4">All Users</h6>

        

            <div class="table-responsive">
            <table id="users-table" class="table">
                        <thead>
                            <tr>
                            
                                <th scope="col">User</th>
                 
                                <th scope="col">Type</th>
                            
                                <th scope="col">Status</th>
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
    // Initialize DataTable
    var table = $('#users-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?php echo $urlval; ?>ajax/fetch_users.php",
            "type": "POST",
            "data": function(d) {
                d.search = $('input[name="search"]').val();
            }
        },
        "columns": [
           
            { "data": "username" },
        
            { "data": "type" },
          
            { "data": "verified" },
            { "data": "action", "sortable": false } 
        ]
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
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


</script>



</body>

</html>