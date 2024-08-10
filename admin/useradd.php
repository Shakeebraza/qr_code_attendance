<?php
include_once('header.php');

?>

<div class="container-fluid pt-4 px-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add User</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Add User</h6>
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Number</label>
                        <input type="number" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">User Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="0">User</option>
                            <option value="2">Supervisor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="verified">
                            <option value="0">Deactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Upload Files</label>
                        <input class="form-control bg-dark text-light" type="file" id="formFileMultiple" name="profile" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: '<?php echo $urlval?>ajax/add-user.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response === 'success') {
                    alert('User added successfully!');
                    $('#addUserForm')[0].reset();
                } else {
                    alert('Error adding user: ' + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('AJAX error: ' + textStatus);
            }
        });
    });
});

</script>

<?php
include_once('footer.php');
?>
