<?php require_once('header.php');

if(isset($_POST['submit_btn'])){
    $class_id = $_POST['select_class'];
    if(isset($_POST['select_subject'])){
        $subject_id = $_POST['select_subject'];
    }
    else{
        $subject_id = '';
    }
    $att_date = $_POST['att_date'];

    // Count Attendance
    $stm=$pdo->prepare("SELECT * FROM attendance WHERE class_id=? AND subject_id=? AND attendance_date=?");
    $stm->execute(array($class_id,$subject_id,$att_date));
    $attCount = $stm->rowCount();
    // By Default
    $studentCount = NULL;

    if(empty($class_id)){
        $error = "Select Class is Required!";
    }
    else if(empty($subject_id)){
        $error = "Select Subject is Required!";
    }
    else if(empty($att_date)){
        $error = "Date is Required!";
    }

    else if($attCount != 1){
        $error = "Attendance not Found!";
    }
    else{
        $stm=$pdo->prepare("SELECT * FROM attendance WHERE class_id=? AND subject_id=? AND attendance_date=?");
        $stm->execute(array($class_id,$subject_id,$att_date));
        $studentCount = $stm->rowCount();
        $studentList = $stm->fetchAll(PDO::FETCH_ASSOC);  
    } 
}

?>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-account"></i>                 
    </span>
    Attendance History
  </h3> 
</div>
<div class="row">
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
    <div class="card-body">  
        <?php if(isset($error)) :?>
        <div class="alert alert-danger"><?php echo $error;?></div>
        <?php endif;?>
        <?php if(isset($success)) :?>
        <div class="alert alert-success"><?php echo $success;?></div>
        <?php endif;?>
        <form class="forms-sample" method="POST" action="">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="select_class">Select Class:</label>
                        <select name="select_class" class="form-control" id="select_class">
                            <option value="">Select Class</option>
                            <?php  
                                $stm = $pdo->prepare("SELECT id,class_name FROM class ORDER BY class_name ASC");
                                $stm->execute();
                                $classList = $stm->fetchAll(PDO::FETCH_ASSOC);
                                $i=1;
                                foreach($classList as $list) :
                            ?>
                            <option
                            <?php 
                            if(isset($_POST['select_class']) AND $_POST['select_class'] == $list['id']){
                                echo "selected";
                            }
                            ?>
                            value="<?php echo $list['id'];?>"><?php echo $list['class_name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="select_subject">Select Subject:</label> 
                        <select name="select_subject" class="form-control" id="select_subject">
                        <?php 
                            if(isset($_POST['select_subject'])){
                                echo '<option value="'.$_POST['select_subject'].'">'.getSubjectName($_POST['select_subject']).'</option>';
                            }
                            ?>
                        </select>
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="att_date">Select Date:</label>
                        <input type="date" value="<?php 
                            if(isset($_POST['att_date'])){
                                echo $_POST['att_date'];
                            }
                            ?>" name="att_date" class="form-control" id="att_date">
                    </div> 
                </div>
                <div class="col-md-3">
                    <div class="form-group"> 
                        <button type="submit" name="submit_btn" class="btn btn-gradient-primary mr-2">Search</button> 
                    </div>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

<?php if(isset($_POST['submit_btn']) AND $studentCount != NULL) : ?>

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body"> 
            Teacher Name: <?php echo getTeacherInfo($studentList[0]['teacher_id'],'name') ;?>
            <br><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th> 
                        <th>Absent</th>
                        <th>Present</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i=1;  
                        $stList = $studentList[0]['student_data'];
                        $stList = json_decode($stList,true); 
                        foreach($stList as $newList) :

                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><?php echo $newList['name'];?> </td> 
                        <td><?php 
                        if( $newList['status'] == 0){
                            echo '<i class="mdi mdi-window-close"></i>';
                        }

                       ;?> </td>
                        <td><?php  if( $newList['status'] == 1){
                            echo '<i class="mdi mdi-check"></i>';
                        }?> </td>
                    </tr>
                    <?php $i++; endforeach;?>
                </tbody>
            </table> 
        </div>
    </div>
</div> 

<?php endif; ?>

</div>

<?php require_once('footer.php'); ?>
<script>
    $('#select_class').change(function(){
        let att_class_id = $(this).val();  
        $.ajax({
            type: "POST",
            url:'ajax.php',
            data: {
                att_class_id:att_class_id
            },
            success:function(response){
                let data = response;
                $('#select_subject').html(data); 
            }
        });  
    });
</script> 