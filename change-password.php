<?php require_once('header.php'); 

if(isset($_POST['change_btn'])){
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confrim_new_password = $_POST['confrim_new_password'];

    $admin_id = $_SESSION['admin_loggedin'][0]['id'];

    $statement=$pdo->prepare("SELECT password FROM admin WHERE id=?");
    $statement->execute(array($admin_id));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $db_password = $result[0]['password'];


    if(empty($current_password)){
        $error = "Current Password is Required!";
    }
    else if(empty($new_password)){
        $error = "New Password is Required!";
    }
    else if(empty($confrim_new_password)){
        $error = "Confrim New Password is Required!";
    }
    else if ($new_password != $confrim_new_password){
        $error = "New Password and Confrim New Password Does't metch!";
    }
    else if(SHA1($current_password) != $db_password){
        $error = "Current Password is Wrong!";
    }
    else{
        $new_password = SHA1($confrim_new_password);

        $stm=$pdo->prepare("UPDATE admin SET password=? WHERE id=?");
        $stm->execute(array($new_password,$admin_id));
        $success = "Password Change Successfully!";

    }
    
}

?>

<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-lock"></i>                 
    </span>
    Change Password
  </h3>
</div>

<div class="row">
<div class="col-md-6 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">
        <?php if(isset($error)) :?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(isset($success)) :?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form class="forms-sample" method="POST" action="">
            <div class="form-group">
                <label for="c_password">Current Password</label>
                <input type="password" name="current_password" class="form-control" id="c_password" placeholder="Current Password">
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password">
            </div>
            <div class="form-group">
                <label for="c_new_password">Confrim New Password</label>
                <input type="password" name="confrim_new_password" class="form-control" id="c_new_password" placeholder="Confrim New Password">
            </div>

            <button type="submit" name="change_btn" class="btn btn-gradient-primary mr-2">Change Password</button>
        </form>
    </div>
    </div>
</div>
</div>

<?php require_once('footer.php'); ?>
