<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('location: login.php');
}
?>

<?php include 'header.html' ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper p-3">
    <!-- Content Header (Page header) -->   
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">BLOG Listing</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="">
                    <a href="add.php" class="btn btn-success">Create New Blog Post</a>
                  </div>                <br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                    <?php
                    if(isset($_GET['pageno'])){
                        $pageno= $_GET['pageno'];
                    }
                    else{
                      $pageno=1;
                    }

                    $numOfRec=1;
                    $offSet=($pageno -1 )* $numOfRec;
                    
                    if(empty($_POST['search'])){
                      $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                      $stmt->execute();
                      $rawResult= $stmt->fetchAll();
                      $total_page= ceil(count($rawResult)/$numOfRec);
                      
                    $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offSet,$numOfRec");
                    $stmt->execute();
                    $result= $stmt->fetchAll();
                    }
                    else{
                      $searchKey=$_POST['search'];
                        $stmt=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
                        $stmt->execute();
                        $rawResult= $stmt->fetchAll();
                        $total_page= ceil(count($rawResult)/$numOfRec);
  
                      $stmt=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offSet,$numOfRec");
                      $stmt->execute();
                      $result= $stmt->fetchAll();
                    }

                    ?>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Desciption</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        if($result){
                          $i=1;
                          foreach($result as $value){?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $value['title']; ?></td>
                      <td>
                        <?php echo substr($value['content'],0, 50); ?>
                      </td>
                      <td>
                        <div class="row">
                          <div class="btn-group">
                            <div class="container">
                              <a href="edit.php?id=<?php echo $value['id']; ?>" class="btn btn-warning">Edit</a>
                            </div>
                            <div class="container">
                              <a href="delete.php?id=<?php echo $value['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure To Detete')">Delete</a>
                            </div>
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

                <nav aria-label="Page navigation" class="mt-3 float-right">
                  <ul class="pagination">
                    <li class="page-item">
                      <a href="?pageno=1" class="page-link">First</a>
                    </li>
                    <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                      <a href="<?php if($pageno <=1){echo '#';}else{echo '?pageno='.($pageno-1);} ?>" class="page-link">Previous</a>
                    </li>
                    <li class="page-item active">
                      <a href="" class="page-link"><?php echo $pageno ?></a>
                    </li>
                    <li class="page-item <?php if($pageno >= $total_page){echo 'disabled';} ?>">
                      <a href="<?php if($pageno >= $total_page){echo '#';}else{echo '?pageno='.($pageno+1);} ?>" class="page-link">Next</a>
                    </li>
                    <li class="page-item">
                      <a href="?pageno=<?php echo $total_page?>" class="page-link">Last</a>
                    </li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include 'footer.html' ?>