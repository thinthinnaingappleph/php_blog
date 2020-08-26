<?php
  session_start();
  require '../config/config.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if($_SESSION['role']!=1){
      header('Location: login.php');
  }

  if($_POST){
    $id= $_POST['id'];
    $name= $_POST['name'];
    $email= $_POST['email'];
    if(empty($_POST['role'])){
      $role = 0;
    }else{
      $role = 1;
    }

    $stmt=$pdo->prepare('SELECT * FROM users WHERE email=:email AND id!=:id');
    $stmt->execute(array(':email' => $email,':id' =>$id));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user){
      echo "<script>alert('Email Duplicate');</script>";
    }else{
      $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
      $result = $stmt->execute();
      if($result){
        echo "<script>alert('Successfully updated');window.location.href='user-list.php';</script>";
      }
    }
  }
  $stmt=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchAll();
 ?>
<?php include('header.php'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
              <form class="" action="" method="post" enctype="multipart/form-data">
                <div class="card-header">
                  <h3 class="card-title">Edit User Form</h3>
                </div>
                <div class="card-body">
                    <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                    <div class="form-group">
                      <label for="">Name</label>
                      <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="">Email</label><br/>
                        <input type="email" class="form-control" name="email" value="<?php echo $result[0]['email'] ?>" required>
                    </div>
                    <div class="form-group">
                      <label for="role">Admin</label><br/>
                      <input type="checkbox" name="role"
                      <?php
                        if($result[0]['role']=='1')  {
                          echo 'checked="checked"';
                        }?>
                      >
                    </div>
                </div>
                <div class="card-footer text-right">
                  <input type="submit" name="" value="SUMBIT" class="btn btn-sm btn-success">
                  <a href="user-list.php" type="button" class="btn btn-sm btn-warning">Back</a>
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
