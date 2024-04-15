<?php 
 session_start();
   $page_title = 'Password Change From';
  include('includes/header.php');
  include('includes/navbar.php');
?>
 <div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
               <?php 
                        if(isset($_SESSION['status']))
                         { 
                            ?>
                            <div class="alert alert-success alert-dismissible">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <h4><?= $_SESSION['status'];?></h4>
                            </div>
                            <?php
                            unset($_SESSION['status']);
                         }
                    ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                    <form action="password-reset-code.php" method="POST">
                        <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];} ?>">
                         <div class="form-group mb-3">
                            <label for="">Email Address</label>
                            <input type="email" name="email"   value="<?php if(isset($_GET['email'])){echo $_GET['email'];}?>"  class="form-control">
                         </div>
                         <div class="form-group mb-3">
                            <label for="">New Password</label>
                            <input type="text" name="new_password" class="form-control">
                         </div>
                         <div class="form-group mb-3">
                            <label for="">Confirm Password</label>
                            <input type="text" name="confirm_password" class="form-control">
                         </div>
                         <div class="form-group">
                         <button type="submit" name="password_update" class="btn btn-success w-100">Update Password</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

<?php  include('includes/footer.php'); ?>