<?php 
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Groopy | Project 1</title>

    <!-- Bootstrap imports -->
    <link href="../../includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../../includes/bootstrap/js/bootstrap.min.js" rel="stylesheet">

    <!-- jQuery imports -->
    <link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    <script src="../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
    <script src="../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.js"></script>
    <script src="../../includes/jquery.dataTables.js"></script>
       
    <!-- Page specific CSS -->
    <link href="../../shared/css/tabs.css" rel="stylesheet">
   	<link rel="stylesheet" type="text/css" href="../../shared/css/user.css">
    <style type="text/css">
		@import "../../shared/css/demo_table.css";
		.masthead{
			margin-left: -75px;
			width: 1290px;
		}
		.tab-pane{
			padding-left: 10px;
		}
		.inviteIcons{
			margin-left: 20px;
			margin-right: 15px;
		}
		.icons{
			margin-right: 15px;
		}
		#discussion{
		  border: 2px solid #555;
		  border-radius: 20px;
		  width: 980px;
		  height: 50px;
		  margin-bottom: 10px;
		  margin-left: 100px;
		  padding: 0 20px;
	  }
	  #discussion h3{
		  margin-top: 10px;
	  }
	  #discussion a:hover{
		  text-decoration: none;
		  
	  }
	  .discussion:hover{
		  background: #e6e6e6;
	  }
	  #date{
		  padding-top: 8px;
		  font-size: 13px;
		  float: right;
		  color: #555;
	  }
	</style>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#datatables').dataTable();
		})
    </script>
  </head>

  <body>

	<?php require_once("../../shared/php/navbar.php"); ?>

    <div class="container">
      <div class="masthead">
        <h2 class="text-muted">Web Dev 
        	<button class="inviteIcons" data-toggle="modal" data-target="#addMemberModal"><img src="../../shared/images/addMember.png" title="Invite Members"></button>
            <!-- google hangout button-->
            <script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
            <div id="hangoutIcon" class="hangoutIcon"></div>
            <script type="text/javascript">
                gapi.hangout.render('hangoutIcon', { 'render': 'createhangout', 'widget_size':70 });
            </script>
        </h2>
<!--ADD Member Modal -->
        <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Invite A Member</h4>
              </div>
              <div class="modal-body">
               <form method="post" action="addMember.php">
                    <p>
                        <label for="fName">First Name: </label>
                        <input type="text" name="fName" id="fName"  required autofocus/>
                    </p>
                    <p>
                        <label for="lName">Last Name: </label>
                        <input type="text" name="lName" id="lName"  required/>
                    </p>
                    <p>
                        <label for="email">Email: </label>
                        <input type="email" name="email" id="email" required />
                    </p>
                    <p>
                      <input type="submit" name="submit" id="submit" value="Send Invite" class="btn btn-danger" />
                      <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                    </p>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <ul id="tabs" class="nav nav-tabs nav-justified">
          <li class="active"><a href="#toDo" data-toggle="tab">To Do</a></li>
          <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
          <li><a href="#uploads" data-toggle="tab">Uploads</a></li>
          <li><a href="#forum" data-toggle="tab">Forum</a></li>
          <li><a href="#contact" data-toggle="tab">Contact</a></li>
        </ul>
        <div id="tab-content" class="tab-content">
        <div class="tab-pane active" id="toDo">
            <h3>Derick's To Do List for Web Dev</h3> <!--TODO: Project 1 & Derick should be a db pull-->
            <table id="datatables" class="display">
            <thead>
                <tr>
                    <th>Deadline</th>
                    <th>Task</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$stmt2 = mysqli_query($connection,"SELECT * FROM tasks WHERE assignedTo = 'Derick'"); /*TODO: this shouldn't be Derick instead it should be USER that has logged in!*/
                while($row = $stmt2->fetch_assoc()){ 
                ?>
                    <tr>
                        <td><?=$row['deadline']?></td>
                        <td><?=$row['task']?></td>

                    </tr>
                <?php 
                }			
                ?>
            </tbody>
        </table>
        </div>
        
        <div class="tab-pane" id="tasks">
        <br/>
        <button class="icons" data-toggle="modal" data-target="#addTaskModal"><img src="../../shared/images/addTask.png" title="Add Task"></button>
        <button class="icons" data-toggle="modal" data-target="#deleteModal"><img src="../../shared/images/delete.png" title="Delete Task"></button>
        <button class="icons" data-toggle="modal" data-target="#editModal"><img src="../../shared/images/edit.png" title="Edit Task"></button>

      <!--ADD Task Modal -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">New Task</h4>
              </div>
              <div class="modal-body">
               <form method="post" action="addTask.php">
                    <p>
                        <label for="task">Task Description: </label> <!--TODO: check if the field task is empty before adding into db-->
                        <textarea rows="4" cols="45" name ="task" id="task" autofocus style="border: 2px solid #CCC; border-radius: 5px;" required> </textarea>
                    </p>
                    <p>
                        <label for="assignedTo">Assign To: </label>
                        <input type="text" name="assignedTo" id="assignedTo"  required/>
                    </p>
                    <p>
                        <label for="deadline">Deadline: </label>
                        <input type="date" name="deadline" id="deadline" required />
                    </p>
                    <p>
                      <input type="submit" name="submit" id="submit" value="Add Task" class="btn btn-danger" />
                      <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                    </p>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        
        <table id="datatables" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Assigned To</th>
                    <th>Task</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$stmt = mysqli_query($connection,"SELECT * FROM tasks");
                while($row = $stmt->fetch_assoc()){ 
                ?>
                    <tr>
                        <td><?=$row['id']?></td>
                        <td><?=$row['assignedTo']?></td>
                        <td><?=$row['task']?></td>
                        <td><?=$row['deadline']?></td>
                    </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
        </div>
       
        <div class="tab-pane" id="uploads">
            <h3>Uploads</h3>
            <p>yellow yellow yellow yellow yellow</p>
        </div>
        
        <div class="tab-pane" id="forum">
            <br/>
            <button class="icons" data-toggle="modal" data-target="#newDiscussion"><img src="../../shared/images/addTask.png" title="New Discussion"></button>
            <!--button class="icons" data-toggle="modal" data-target="#addPost"><img src="../../shared/images/addTask.png" title="Add Message"></button-->
            <br/>
            <!-- New Discussion Modal -->
            <div class="modal fade" id="newDiscussion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Create New Discussion</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="addDiscussion.php">
                        <p>
                          <label for="topic">Topic:</label>
                          <input type="text" name="topic" id="topic" required/>
                        </p>
                        <p>
                          <input type="submit" name="submit" id="submit" value="Create" class="btn btn-danger"/>
                          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                        </p>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Add Post Modal >
            <div class="modal fade" id="addPost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Add Message</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="addPost.php">
                        <p> 
                          <label for="user">User:</label>
                          <input type="text" name="user" id="user" autofocus required/>
                        </p>
                        <p>
                          <label for="msg">Message:</label>
                          <textarea name="msg" id="msg" cols="45" rows="5" style="border: 2px solid #CCC; border-radius: 5px;" required></textarea>
                        </p>
                        <p>
                          <input type="submit" name="submit" id="submit" value="Post message" class="btn btn-danger"/>
                          <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                        </p>
                    </form>
                  </div>
                </div>
              </div>
            </div--> <!--/addPostModal-->
            
            <?php
			$sql = mysqli_query($connection,"SELECT * FROM discussion ORDER BY date DESC");
			
			while($row2 = $sql->fetch_assoc()) {?>
                <div id="discussion" class="discussion">
                    <a href="searchPost.php?topic=<?php echo $row2['topic'];?>">
                    <h3><?php echo $row2['topic'];?><i id="date"><?php echo $row2['date'];?></i></h3> 
                    </a>
                </div> <!--/discussion-->
			<?php }
				/**function callPost(){
				$sql = mysqli_query($connection,"SELECT id FROM discussion");
				$row1 = $sql1->fetch_assoc();
				$sql1 = mysqli_query($connection,"SELECT * FROM post WHERE Discussion_id = '$row1'");
					while($row2 = $sql1->fetch_assoc()) {
					  echo $row2['date'].' <br />';
					  echo $row2['msg'].'<br />';
					  echo '------------------------ <br />';
					}
				}
				if (isset($_GET['searchPost'])) {
					callPost();
				}*/
			?>
            
            
        </div>
        <div class="tab-pane" id="contact">
            <h3>Contact</h3>
            <p>blue blue blue blue blue</p>
        </div> <!--/contact-->
    </div>
</div>
    </div> <!-- /container -->
    <script src="../../includes/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#tabs').tab();
    });
</script>    

      <?php require_once("../../shared/php/footer.php")?>


    
</body>
</html>