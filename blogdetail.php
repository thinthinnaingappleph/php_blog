<?php
  session_start();
  require 'config/config.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }

  if($_SESSION['role'] != 0){
      header('Location: login.php');
  }

  $stmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchAll();

  $blogId = $_GET['id'];

  $cmtstmt=$pdo->prepare("SELECT comments.*,users.name as username FROM comments join users on comments.auther_id = users.id WHERE comments.post_id=$blogId");
  $cmtstmt->execute();
  $cmtResult=$cmtstmt->fetchAll();


  if($_POST){
    $comment = $_POST['comment'];
    $stmt=$pdo->prepare('INSERT INTO comments(content,auther_id,post_id) VALUES (:content,:auther_id,:post_id)');
    $result=$stmt->execute(
      array(':content'=>$comment,':auther_id'=>$_SESSION['user_id'],':post_id'=>$blogId)
    );
    if($result){
      header('Location: blogdetail.php?id='.$_GET['id']);
    }

  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <div class="content-wrapper ml-0">
    <!-- Main content -->
    <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="float:none;text-align:center;">
                    <h4><?php echo $result[0]['title'] ?></h4>
                </div>
                <!-- /.user-block -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'] ?>" alt="Photo">
                <br/><br/>
                <p><?php echo $result[0]['content'] ?></p>
                <h3>Comments</h3><hr/>
                <a href="index.php" type="button" class="btn btn-sm btn-default">Go Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <div class="card-comment">
                  <?php
                  foreach ($cmtResult as $value) {
                  ?>
                  <div class="comment-text ml-0">
                    <span class="username">
                      <?php echo $value['username'] ?>
                      <span class="text-muted float-right"><?php echo $value['created_at'] ?></span>
                    </span><!-- /.username -->
                    <?php echo $value['content'] ?>
                  </div>
                  <!-- /.comment-text -->
                  <?php
                  }
                   ?>

                </div>
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer ml-0">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-sm btn-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
