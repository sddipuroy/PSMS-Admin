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
    $exam_id = $_POST["select_exam"];

    // Attendance Count
    if( $exam_id == 1){
     $stm =$pdo->prepare("SELECT * FROM exam_marks_1 WHERE class_id=? AND subject_id=? AND teacher_id=? AND exam_id=?");
     $stm->execute(array($class_id,$subject_id,$teacher_id,$exam_id));
     $exCount = $stm->rowCount();
    }
    else if( $exam_id == 2){
     $stm =$pdo->prepare("SELECT * FROM exam_marks_2 WHERE class_id=? AND subject_id=? AND teacher_id=? AND exam_id=?");
     $stm->execute(array($class_id,$subject_id,$teacher_id,$exam_id));
     $exCount = $stm->rowCount();
    }
    else if( $exam_id == 3){
     $stm =$pdo->prepare("SELECT * FROM exam_marks_3 WHERE class_id=? AND subject_id=? AND teacher_id=? AND exam_id=?");
     $stm->execute(array($class_id,$subject_id,$teacher_id,$exam_id));
     $exCount = $stm->rowCount();
    }
    
    //  $today = date('Y-m-d');
    
    //  by Default
     $studentCount = NULL;
    
    if(empty($class_id)){
        $error = "Select Class is Required!";
    }
    else if(empty($subject_id)){
        $error = "Select Subject is Required!";
    }
    else if(empty($exam_id)){
        $error = "Select Exam is Required!";
    }
    // else if( $att_date != $today){
    //     $error = "Attandance Date is Wrong!";
    // }
    else if($exCount != 0 ){
        $error = "Allready Submit The Subject Marks!";
    }
    else{
        $stm = $pdo->prepare("SELECT  id ,name,roll FROM students WHERE current_class=?");
        $stm ->execute(array($class_id));
        $studentCount = $stm->rowCount();
        $studentList = $stm->fetchAll(PDO::FETCH_ASSOC);

        // print_r($studentList);
    }
   
 }

  if(isset($_POST['marks_submit'])){
    $student_id = $_POST['student_id'];
    $marks = $_POST['marks'];

    $class_id = $_POST["class_id"];
    $subject_id = $_POST["subject_id"];
    $exam_id = $_POST["exam_id"];

    $length = count($student_id) ;

    if($exam_id == 1){
        for($i=0;$i<$length;$i++){
            $insert = $pdo->prepare("INSERT INTO exam_marks_1 (teacher_id,class_id,subject_id,exam_id,st_id,st_marks)VALUES (?,?,?,?,?,?)");
            $insert ->execute(array($teacher_id,$class_id,$subject_id,$exam_id,$student_id[$i],$marks[$i]));
    
        }
    }
    else if($exam_id == 2){
        for($i=0;$i<$length;$i++){
            $insert = $pdo->prepare("INSERT INTO exam_marks_2 (teacher_id,class_id,subject_id,exam_id,st_id,st_marks)VALUES (?,?,?,?,?,?)");
            $insert ->execute(array($teacher_id,$class_id,$subject_id,$exam_id,$student_id[$i],$marks[$i]));
    
        }
    }
    else if($exam_id == 3){
        for($i=0;$i<$length;$i++){
            $insert = $pdo->prepare("INSERT INTO exam_marks_3 (teacher_id,class_id,subject_id,exam_id,st_id,st_marks)VALUES (?,?,?,?,?,?)");
            $insert ->execute(array($teacher_id,$class_id,$subject_id,$exam_id,$student_id[$i],$marks[$i]));
    
        }
    }
    
     
   
    $success ="Marks Submit success!";


  }




?>


<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-account"></i>                 
    </span>
     Submit Exam Marks
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
                        <label for="select_exam">Select Date:</label>
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
                      <button type="submit" name="submit_btn" class="btn btn-gradient-primary mr-2">Submit</button>
                    </div>
                </div>
            </div>
            
            </form>
        </div>
        </div>
    </div>

  <?php if(isset($_POST['submit_btn']) AND $studentCount != NULL):?>
  <?php if($studentCount>0):?>

    <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <form action="" method="POST">
                <table class="table table-borderd">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name:</th>
                            <th>Student Marks:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $i=1; 
                         $a=0; 
                         foreach($studentList as $newList):
                        ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $newList['name'] ;?></td>
                            <input type="hidden" value="<?php echo $newList['id'] ;?>" name="student_id[]">
                            <input type="hidden" value="<?php echo $_POST['select_class'] ;?>" name="class_id">
                            <input type="hidden" value="<?php echo $_POST['select_subject'] ;?>" name="subject_id">
                            <input type="hidden" value="<?php  echo $_POST['select_exam'] 
                            ;?>" name="exam_id"> 


                           
                            <td><input class="form-control" type="number" name="marks[<?php echo $a;?>]"></td>
                        </tr>
                        <?php $i++;$a++;  endforeach; ?>
                    </tbody>
                </table>
                <br>
                <br>
                <div class="form-group">
                    <input type="submit" name="marks_submit" class="btn btn-success btn-sm" value="Submit Marks">
                </div>
             </form>
            </div> 
        </div> 
        
        <?php else:?>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="alert alert-danger">No Studdent Found!</div>
            </div>
        <?php endif;?>
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