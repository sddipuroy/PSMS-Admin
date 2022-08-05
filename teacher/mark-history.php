<?php require_once('header.php') ;

$teacher_id = $_SESSION['teacher_loggedin'][0]["id"];
 
 if(isset($_POST['submit_btn'])){
    $class_id = $_POST["select_class"];
    if(isset($_POST["select_subject"])){
        $subject_id = $_POST["select_subject"];
    }
    else{
        $subject_id ='';
    }
    $select_exam = $_POST["select_exam"];


    if($select_exam ==1){
        $stm = $pdo->prepare("SELECT * FROM exam_marks_1 WHERE class_id=? AND subject_id=? AND exam_id=?");
        $stm ->execute(array($class_id,$subject_id,$select_exam));
        $exCount = $stm->rowCount();
    }
    else if($select_exam ==2){
        $stm = $pdo->prepare("SELECT * FROM exam_marks_2 WHERE class_id=? AND subject_id=? AND exam_id=?");
        $stm ->execute(array($class_id,$subject_id,$select_exam));
        $exCount = $stm->rowCount();
    }
    else if($select_exam ==3){
        $stm = $pdo->prepare("SELECT * FROM exam_marks_3 WHERE class_id=? AND subject_id=? AND exam_id=?");
        $stm ->execute(array($class_id,$subject_id,$select_exam));
        $exCount = $stm->rowCount();
    }
    
    //  by Default
     $studentCount = NULL;
    
    if(empty($class_id)){
        $error = "Select Class is Required!";
    }
    else if(empty($subject_id)){
        $error = "Select Subject is Required!";
    }
    else if(empty($select_exam)){
        $error = "Select Exam is Required!";
    }
    // else if( $att_date != $today){
    //     $error = "Attandance Date is Wrong!";
    // }
    else if( $exCount == 0 ){
        $error = "Result Not Fount";
    }
    else{
        if($select_exam ==1){
            $stm = $pdo->prepare("SELECT * FROM exam_marks_1 WHERE class_id=? AND subject_id=? AND exam_id=?");
            $stm ->execute(array($class_id,$subject_id,$select_exam));
            $studentCount = $stm->rowCount();
            $studentList = $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        else if($select_exam ==2){
            $stm = $pdo->prepare("SELECT * FROM exam_marks_2 WHERE class_id=? AND subject_id=? AND exam_id=?");
            $stm ->execute(array($class_id,$subject_id,$select_exam));
            $studentCount = $stm->rowCount();
            $studentList = $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        else if($select_exam ==3){
            $stm = $pdo->prepare("SELECT * FROM exam_marks_3 WHERE class_id=? AND subject_id=? AND exam_id=?");
            $stm ->execute(array($class_id,$subject_id,$select_exam));
            $studentCount = $stm->rowCount();
            $studentList = $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        

       
    }
   
 }

  if(isset($_POST['attendance_submit'])){
    $student_id = $_POST['student_id'];
    $student_name = $_POST['student_name'];
    $status = $_POST['status'];

    // print_r($student_id);
    // print_r($student_name);
    // print_r($status);
    $length = count($student_id) ;
    $studentData=[];
    for($i=0;$i<$length;$i++){
        $studentData[$i]['id']=$student_id[$i];
        $studentData[$i]['name']=$student_name[$i];
        $studentData[$i]['status']=$status[$i];
    }
    // echo "<pre>";
    // print_r($studentData);
    // echo "</pre>";



    $final_st_data = json_encode($studentData);
    $class_id = $_POST["class_id"];
    $subject_id = $_POST["subject_id"];
    $select_exam = $_POST["select_exam"];   
     
    $insert = $pdo->prepare("INSERT INTO attendance (class_id,subject_id,attendance_date,student_data,teacher_id)VALUES (?,?,?,?,?)");
    $insert ->execute(array($class_id,$subject_id,$att_date,$final_st_data,$teacher_id));
    $success ="Attendance Submit success!";


  }




?>


<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-account"></i>                 
    </span>
    Marks History
  </h3>
</div>
   <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <?php if(isset($error)) :?>
                <div class="alert alert-danger">
                    <?php echo $error ;?>
                </div>   
            <?php endif;?>
            <?php if(isset($success)) :?>
                <div class="alert alert-success">
                    <?php echo $success ;?>
                </div>   
            <?php endif;?>
            <form class="forms-sample" method="POST" action="">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="select_class">Select Class:</label>
                         <select name="select_class" class="form-control" id="select_class">
                         <option value="">Select Class</option>
                        <?php
                           $stm = $pdo->prepare("SELECT DISTINCT class_name FROM class_routine WHERE teacher_id =?");
                           $stm->execute(array($teacher_id));
                           $i=1;
                           $a=0;
                           $classList=$stm->fetchAll(PDO::FETCH_ASSOC);

                           foreach($classList as $list) :?>
                            <option 
                            <?php 
                             if(isset($_POST['select_class'] ) AND $_POST['select_class'] == $list['class_name']){
                                echo 'selected';               
                             }
                            ?>
                            value="<?php echo $list['class_name'];?>"><?php echo getClassName($list['class_name'],'class_name');?></option>
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
                    <select name="select_exam" id="select_exam" class="form-control">
                             <option <?php 
                            if(isset($_POST['select_exam'] ) AND $_POST['select_exam']  == 1){
                                echo 'selected';               
                             }
                            ?>
                            value="1">1st Term Exam</option>
                            <option 
                            <?php 
                            if(isset($_POST['select_exam'] ) AND $_POST['select_exam'] == 2){
                                echo 'selected';               
                             }
                            ?>
                            value="2">2nd Term Exam</option>
                            <option 
                            <?php 
                            if(isset($_POST['select_exam'] ) AND $_POST['select_exam'] == 3){
                                echo 'selected';               
                             }
                            ?>
                            value="3">Final Exam</option>
                        </select>
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

  <?php if(isset($_POST['submit_btn']) AND $studentCount != NULL):?>

    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <table class="table table-borderd">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name:</th>
                            <th>Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1; 
                            foreach($studentList as $newList):
                        ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo Student($newList['st_id'],'name')  ;?></td>
                            <td><?php echo $newList['st_marks'] ;?></td>
                               
                        </tr>
                        <?php $i++;  endforeach; ?>
                    </tbody>
                </table>
            </div> 
        </div> 
        <?php endif;?>

     </div>  
 

<?php require_once('footer.php') ;?>  
<script>
    $('#select_class').change(function(){
       let class_id = $(this).val();
       let teacher_id = '<?php echo $teacher_id; ?>';

       $.ajax({
        type: 'POST',
        url: 'ajax.php',
        data:{
            teacher_id:teacher_id,
            class_id:class_id
        },
        success:function(response){
            let data = response;
            // console.log(data);
            $('#select_subject').html(data);
        }
       })
    });
</script>