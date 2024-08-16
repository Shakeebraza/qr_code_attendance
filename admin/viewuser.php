<?php
include_once('header.php');

if (!isset($_GET['userid']) || empty($_GET['userid'])) {
    header('Location: ../login.php');
    exit(); 
} else {
    $id = base64_decode(base64_decode($_GET['userid']));

    $find = $funObject->FindUser($id);

    $files = $funObject->GetUserFiles($id); 
    if ($find['count'] < 1) {
        header('Location: ../login.php');
        exit(); 
    }

    if(!empty($find['records'][0]['profile'])){
        $imageUrl=$urlval.$find['records'][0]['profile'];
    }else{
        $imageUrl=$urlval.'admin/img/user.jpg';

    }
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID) {
        header('Location: ../login.php');
        exit();
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">View User</li>
        </ol>
    </nav>

    <!-- User Details Container -->
    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">User Details</h6>
                <!-- Profile Image -->
                <div class="d-flex justify-content-center mb-4">
                    <img src="<?php echo $imageUrl; ?>" alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" value="<?php echo $find['records'][0]['username']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="actual_name" class="form-label">Actual Name</label>
                    <input type="text" class="form-control" id="actual_name" value="<?php echo htmlspecialchars($find['records'][0]['actual_name'] ?? 'Not Set Actual Name'); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="work_name" class="form-label">Work Name</label>
                    <input type="text" class="form-control" id="work_name" value="<?php echo htmlspecialchars($find['records'][0]['work_name'] ?? 'Not Set Work Name'); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Number</label>
                    <input type="email" class="form-control" id="email" value="<?php echo $find['records'][0]['email']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" class="form-control" id="status" value="<?php echo $find['records'][0]['type'] == 0 ? 'Deactive' : 'Active'; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="usertype" class="form-label">User Type</label>
                    <input type="text" class="form-control" id="usertype" value="<?php echo $find['records'][0]['verified'] == 0 ? 'User' : 'Supervisor'; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="verified" class="form-label">Verified</label>
                    <input type="text" class="form-control" id="verified" value="<?php echo $find['records'][0]['verified'] == 0 ? 'Not Verified' : 'Verified'; ?>" readonly>
                </div>
                <!-- User Files -->
                <div class="mb-3">
                    <label for="userfiles" class="form-label">Uploaded Files</label>
                    <ul class="list-group">
                        <?php if ($files['count'] > 0) : ?>
                            <?php foreach ($files['records'] as $file) : ?>
                                <li class="list-group-item bg-dark text-light">
                                    <a href="<?php echo $urlval.$file['file_path']; ?>" target="_blank" class="text-light">
                                        <?php echo $file['filename']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="list-group-item bg-dark text-light">No files uploaded.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once('footer.php');
?>
</body>
</html>
