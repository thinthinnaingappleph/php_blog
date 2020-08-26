<?php
session_start();
require '../config/config.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}
 ?>
<?php include('header.html'); ?>

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title">Blog Listings</h3>
              <a href="add.php" type="button" class="btn btn-sm btn-success ml-auto">New Blog Post</a>
            </div>
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $numOfRecs=5;
              $offset=($pageno-1) * $numOfRecs;

              if(empty($_POST['search'])){
                $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY  id DESC');
                $stmt->execute();
                $rawResult= $stmt->fetchAll();

                $total_pages=ceil(count($rawResult) / $numOfRecs);

                $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY  id DESC LIMIT $offset,$numOfRecs");
                $stmt->execute();
                $result= $stmt->fetchAll();
              }else{
                $searchKey=$_POST['search'];
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY  id DESC");
                $stmt->execute();
                $rawResult= $stmt->fetchAll();

                $total_pages=ceil(count($rawResult) / $numOfRecs);

                $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY  id DESC LIMIT $offset,$numOfRecs");
                $stmt->execute();
                $result= $stmt->fetchAll();
              }

             ?>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th style="width: 40px">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    if($result){
                      $i = 1;
                      foreach ($result as $value) {
                        ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo $value['title']; ?></td>
                          <td><?php echo substr($value['content'],0,100); ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="edit.php?id=<?php echo $value['id']?>"type="button" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                              </div>
                              <div class="container">
                                <a href="delete.php?id=<?php echo $value['id']?>" onclick="return confirm('Are you sure you want to delete this item?');" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <?php
                        $i++;
                      }
                    }
                   ?>

                </tbody>
              </table>
              <br/>
              <nav aria-label="Page navigation example" style="float:right">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno <= 1){echo '#';} else{echo '?pageno='.($pageno-1);} ?>">
                      Previous
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno ?></a></li>
                  <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno >= $total_pages){echo '#';} else {echo '?pageno='.($pageno+1);} ?>">
                      Next
                    </a>
                  </li>
                  <li class="page-item"><a class="page-link" href="<?php echo '?pageno='.$total_pages ?>">Last</a></li>
                </ul>
              </nav>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

<?php include('footer.html'); ?>
