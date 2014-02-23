<DOCTYPE html>
<html>
<head>
    <!-- Bootstrap imports -->
    <link href="../../includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../../includes/bootstrap/js/bootstrap.min.js" rel="stylesheet">
      
    <!-- jQuery imports -->
    <link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    <script src="../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
    <script src="../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.js"></script>
    
    <!-- Page specific CSS -->
   	<link rel="stylesheet" type="text/css" href="../../shared/css/user.css">

<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_GET['topic'])) {
	$topic = $_GET['topic'];
	$sql = mysqli_query($connection,"SELECT id FROM discussion WHERE topic = '$topic'");
	$id = mysqli_fetch_row($sql);
?>
	<style type="text/css">
		body{
			padding-left: 20px;
		}
	</style>
</head>
<body>
    <h3><?php echo $topic;?></h3>
    <button class="icons" data-toggle="modal" data-target="#addPost"><img src="../../shared/images/addTask.png" title="Add Post"></button>
    <br/><br/>
<!-- Add Post Modal -->
            <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Add Post</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="addPost.php">
                        <p>
                          <input type="hidden" name="discussionId" id="discussionId" value="<?php $id[0];?>"/>
                        </p>
                        <p>
                          <label for="msg">Message:</label>
                          <textarea name="msg" id="msg" cols="45" rows="5" style="border: 2px solid #CCC; border-radius: 5px;" autofocus required></textarea>
                        </p>
                        <p>
                          <input type="submit" name="submit" id="submit" value="Post it" class="btn btn-danger"/>
                          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                        </p>
                    </form>
                  </div>
                </div>
              </div>
            </div> <!--/addPostModal-->
    <?php
	$sql1 = mysqli_query($connection,"SELECT * FROM post WHERE Discussion_id = $id[0]");
		while($row2 = $sql1->fetch_assoc()) {
		  echo $row2['date'].' <br />';
		  echo $row2['msg'].'<br />';
		  echo '------------------------ <br />';
		}
	}
	/*header( 'Location: dashboard.php#forum' ) ; // actually the posts should also show up inside the forum tab*/
?>
	<script src="../../includes/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>