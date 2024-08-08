<?php
include_once('header.php');

?>

<div class="container-fluid pt-4 px-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add User</li>
        </ol>
    </nav>

    <!-- Form Container -->
    <div class="d-flex justify-content-center">
        <div class="col-sm-12 col-xl-6">
            <div class="bg-secondary rounded h-100 p-4">
                <h6 class="mb-4">Add User</h6>
                <form>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputRePassword1" class="form-label">Re-Password</label>
                        <input type="password" class="form-control" id="exampleInputRePassword1">
                    </div>
                    
                    <div class="mb-3">
                        <label for="exampleSelect1" class="form-label">Status</label>
                        <select class="form-select" id="status" aria-label="Default select example">
                            <option selected disabled>Open this select menu</option>
                            <option value="0">Deactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exampleSelect2" class="form-label">User Type</label>
                        <select class="form-select" id="usertype" aria-label="Default select example">
                            <option selected disabled>Open this select menu</option>
                            <option value="0">User</option>
                            <option value="2">supervisor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Upload Files</label>
                        <input class="form-control bg-dark text-light" type="file" id="formFileMultiple" multiple>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</div>








 <?php
 include_once('footer.php');
 ?>

 

</body>

</html>