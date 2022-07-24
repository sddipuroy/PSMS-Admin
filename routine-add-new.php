<?php require_once('header.php'); 

if(isset($_POST['create_btn'])){
    $sub_name = $_POST['sub_name'];
    $sub_code = $_POST['sub_code'];
    $sub_type = $_POST['sub_type'];

    //Subject Code Count
    $codeCout = getCount('subjects','code',$sub_code);

    if(empty($sub_name)){
        $error = "Subject Name is Required!";
    }
    else if(empty($sub_code)){
        $error = "Subject Code is Required!";
    }
    else if(empty($sub_type)){
        $error = "Subject Type is Required!";
    }
    else if($codeCout != 0){
        $error = "Subject Code is Already Used!";
    }
    else{
        $insert = $pdo->prepare("INSERT INTO subjects(name,code,type) VALUES (?,?,?)");
        $insert->execute(array($sub_name,$sub_code,$sub_type));
        $success = "Subject Create Success!";
    }
}
?>

<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white mr-2">
      <i class="mdi mdi-airballoon"></i>                 
    </span>
    Add New Routine
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
                        <label for="class_name">Select Class :</label>
                        <?php
                        $stm=$pdo->prepare("SELECT id,class_name FROM class");
                        $stm->execute();
                        $lists = $stm->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <select name="class_name" id="class_name" class="form-control">
                            <?php 
                            foreach($lists as $list):
                            ?>
                            <option value="<?php echo $list['id']; ?>"><?php echo $list['class_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject_name">Select Subject :</label>
                        <select name="subject_name" id="subject_name" class="form-control"></select>
                    </div>

                    <div class="form-group">
                        <label for="time_from">Time From:</label> <br>
                        <input type="time" name="time_from" class="form-control" id="time_from">
                    </div>

                    <div class="form-group">
                        <label for="time_to">Time To:</label> <br>
                        <input type="time" name="time_to" class="form-control" id="time_to">
                    </div>

                    <div class="form-group">
                        <label for="room_no">Room No:</label> <br>
                        <input type="text" name="room_no" class="form-control" id="room_no">
                    </div>
                    
                    <button type="submit" name="create_btn" class="btn btn-gradient-primary mr-2">Create Routine</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
<script>
    $('#class_name').change(function(){
        let class_id = $(this).val();

        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                class_id:class_id
            },
            success:function(response){
                let data = response;
                $('#subject_name').html(data);
                console.log(response);
            }
        });
    });
</script>