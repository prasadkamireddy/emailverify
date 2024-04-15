<?php 
include("authentication.php");
  $page_title = 'Dashboard Page';
  include('includes/header.php');
  include('includes/navbar.php');
?>
 <div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                <div class="card">
                    <div class="card-header">
                        <h3>User Dashboard</h3>
                    </div>
                    <div class="card-body">
                       <h4>Access when you are Logged IN</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<?php  include('includes/footer.php'); ?>