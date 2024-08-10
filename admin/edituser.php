<?php
include_once('header.php');

// Check if 'userid' is set and not empty
if (!isset($_GET['userid']) || empty($_GET['userid'])) {
    header('Location: ../login.php');
    exit(); 
} else {
    // Decode the user ID
    $id = base64_decode($_GET['userid']);

    $find = $funObject->FindUser(base64_decode($id));

    if ($find['count'] < 1) {
        header('Location: ../login.php');
        exit(); 
    }
    if(!empty($find['records'][0]['profile'])){
        $imageUrl=$urlval.$find['records'][0]['profile'];
    }else{
        $imageUrl=$urlval.'admin/img/user.jpg';

    }
}
?>

<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit User</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-center">
    <div class="col-sm-12 col-xl-6">
        <div class="bg-secondary rounded h-100 p-4">
            <h6 class="mb-4">Edit User</h6>
            <!-- Profile Image -->
            <div class="d-flex justify-content-center mb-4">
                <img src="<?php echo $imageUrl; ?>" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
            </div>
            <form id="editUserForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $find['records'][0]['username']; ?>">
                    <input type="hidden" id="userId" name="userId" value="<?php echo base64_decode($id); ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Number</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" value="<?php echo $find['records'][0]['email']; ?>">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <div class="mb-3">
                    <label for="exampleInputRePassword1" class="form-label">Re-Password</label>
                    <input type="password" class="form-control" id="exampleInputRePassword1" name="repassword">
                </div>
                <div class="mb-3">
                    <label for="exampleSelect1" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status" aria-label="Default select example">
                        <option value="0" <?php if ($find['records'][0]['type'] == 0) echo "selected"; ?>>Deactive</option>
                        <option value="1" <?php if ($find['records'][0]['type'] == 1) echo "selected"; ?>>Active</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleSelect2" class="form-label">User Type</label>
                    <select class="form-select" id="usertype" name="usertype" aria-label="Default select example">
                        <option value="0" <?php if ($find['records'][0]['verified'] == 0) echo "selected"; ?>>User</option>
                        <option value="1" <?php if ($find['records'][0]['type'] == 1) echo "selected"; ?>>Supervisor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Upload Files</label>
                    <input class="form-control bg-dark text-light" type="file" id="formFileMultiple" name="files[]" multiple>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#editUserForm').submit(function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create a FormData object with the form data

        $.ajax({
            url: '../ajax/edituser.php', // URL of the PHP script to handle the form data
            type: 'POST',
            data: formData,
            contentType: false, // Prevent jQuery from setting the Content-Type header
            processData: false, // Prevent jQuery from processing the data into a query string
            success: function(response) {
                alert('User updated successfully!');
                // Optionally, you can handle the response here (e.g., redirect or update the UI)
            },
            error: function(xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});
</script>

<?php
include_once('footer.php');
?>
</body>
</html>
