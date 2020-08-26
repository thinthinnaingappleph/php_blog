<?php
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}

if($_POST){
  $file = 'images/'.$_FILES['image']['name'];
  $fileType= pathinfo($file,PATHINFO_EXTENSION);
  if($fileType != 'png' && $fileType != 'jpg' && $fileType != 'jpeg'){
    echo "<script>alert('Image must be png,jpg,jpeg');</script>";
  }else{
    $title = $_POST['title'];
    $content = $_POST['content'];
    $auther_id = $_SESSION['user_id'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],$file);
    $stmt=$pdo->prepare('INSERT INTO posts(title,content,auther_id,image) VALUES (:title,:content,:auther_id,:image)');
    $result=$stmt->execute(
      array(':title'=>$title,':content'=>$content,':auther_id'=>$auther_id,':image'=>$image)
    );
    if($result){
      echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
      // header('Location: index.php');
    }
  }
}
 ?>
<?php include('header.html'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              <form class="" action="add.php" method="post" enctype="multipart/form-data">
                <div class="card-header">
                  <h3 class="card-title">Blog Form</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="">Title</label>
                      <input type="text" class="form-control" name="title" value="" required>
                    </div>
                    <div class="form-group">
                      <label for="">Content</label><br/>
                      <textarea class="form-control" name="content" rows="8" cols="80"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="">Image</label><br/>
                      <input type="file" name="image" value="" required>
                    </div>
                </div>
                <div class="card-footer text-right">
                  <input type="submit" name="" value="SUMBIT" class="btn btn-sm btn-success">
                  <a href="index.php" type="button" class="btn btn-sm btn-warning">Back</a>
                </div>
              </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

<?php include('footer.html'); ?>
