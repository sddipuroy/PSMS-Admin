<?php require_once('header.php'); 
$teacher_id = $_SESSION['teacher_loggedin'][0]['id'];
?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-account-multiple"></i>                 
    </span>
    Salary Payment History 
  </h3> 
  <span class="badge badge-success">Current balance: <?php echo  getTeacherInfo($teacher_id,'balance');?> tk</span>
</div>
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">  
            <table class="table table-bordered" id="Table_Teacher_List">
                <thead>
                    <tr>
                        <th>#</th> 
                        <th>Teacher Name</th> 
                        <th>Salary Amount</th> 
                        <th>Method</th> 
                        <th>Account</th> 
                        <th>Date-Time</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $stm = $pdo->prepare("SELECT * FROM teacher_payment_history WHERE teacher_id=? ORDER BY id DESC");
                    $stm->execute(array($teacher_id));
                    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
                    $i=1;
                    foreach($result as $row) :
                    ?>
                    <tr>
                        <td><?php echo $i;$i++;?></td>
                        <td><?php echo getTeacherInfo($row['teacher_id'],'name') ;?></td>
                        <td><?php echo $row['amount']." tk" ;?></td>
                        <td><?php echo $row['payment_method'] ;?></td>
                        <td><?php echo $row['account_number'] ;?></td>
                        <td><?php echo $row['created_at'] ;?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<?php require_once('footer.php'); ?> 