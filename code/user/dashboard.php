<?php 
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_GET['title'])) {
	$title = $_GET['title'];
	$sql = mysqli_query($connection,"SELECT id FROM project WHERE name = '$title'");
	$id = mysqli_fetch_row($sql);
?>

<?php
/**
php to add member when invite member button is clicked
@author: Lam Lu
**/
	if(isset($_POST["inviteMemberButtonClicked"]) && isset($_POST['emailToInvite']) && isset($_POST['projectId']) && 
			isset($_POST['projectTitle']))
	{
		if($_POST['inviteMemberButtonClicked'] == true)
		{ 
			$kInviteEmail = $_POST['emailToInvite'];
			$kProjectID = $_POST['projectId'];
			$kProjectTitle = $_POST['projectTitle'];
			if ($connection != null)
			{
				/* create a prepared statement */
				if ($stmt = $connection->prepare("Select id from Users where email = ?")) 
				{
					if ($stmt->bind_param("d", $kInviteEmail))
					{
						if ($stmt->execute()) 
						{
							if ($stmt->bind_result($kResultUID))
							{
								if($stmt->fetch())
								{
									$stmt->close();
									$stmt = null;
									
									/* select user ok*/
									if ($stmt = $connection->prepare("Insert into project_user(project_id,user_id,active, activation_key) values (?,?,?,?)")) 
									{
										$kActive = 0;
										$kActivationKey = $activation_key = md5(uniqid(rand(), true));
										if ($stmt->bind_param("ddds", $kProjectID, $kResultUID,$kActive, $kActivationKey))
										{
											if ($stmt->execute()) 
											{
												//insert ok, send invitation email to the user
												require_once("../account/MailAgent.php");
												$subject = "Groopy Project Invitation";
												session_start();
												$kInviteFirstName = $_SESSION['firstName'];
												$kInviteLastName = $_SESSION['lastName'];
												
												$kInviteMessage = "Hello from Groopy Team.\n".$kInviteFirstName." ".$kInviteLastName." has invited you to join ".$kProjectTitle.". Click on the link below if you wish to accept the invitation \n";
												$kLink = "http://localhost:8888/code/user/acceptProjectInvitation.php?key=".$kActivationKey."&accept=true";
												$kInviteMessage= $kInviteMessage.$kLink."\nThank You.\nGroopy Team";
												$kHeader = "From: Groopy <noreply@groopy.com>";
												MailAgent::writeEmail($kInviteEmail,$subject,$kInviteMessage,$kHeader);
											}
										}
									}
								}					
							}
						}
					}
				}
			}
		}
	}
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
		#post{
		  border: 2px solid #555;
		  border-radius: 10px;
		  width: 980px;
		  height: 80px;
		  margin-bottom: 10px;
		  margin-top: 10px;
		  margin-left: 0px;
		  padding: 0 20px;
	  }
	  #post h3{
		  margin-top: 10px;
		  cursor: pointer;
	  }
	  #post a:hover{
		  text-decoration: none; 
	  }
	  #date{
		  padding-top: 8px;
		  font-size: 13px;
		  float: right;
		  color: #555;
	  }
	  #postArea {
		margin-top: 0px;
		margin-left: 160px;
		width: 1040px;
	  }
	  .replyBtn{
		  background: #F33;
		  border: 1px solid #F33;
		  padding: 4px 15px;
		  border-radius: 5px;
		  float: right;
		  margin-top: -35px;
		  margin-right: 50px;
		  color: white;
	  }
	</style>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#datatables').dataTable();
		})
		/*For forum to post
			function searchPost(){
				$.ajax({
					url: "temp.php",
					type: "POST",
					data: { topic: "<?php /*echo $row2['topic'];*/?>", option: "search"},
					cahce: false;
					success: function(response){
						  //do action  
						  $('#forum').html(response);
					}
				});
			}*/
    </script>
  </head>

  <body>

	<?php require_once("../../shared/php/navbar.php"); ?>

    <div class="container">
      <div class="masthead">
        <h2 class="text-muted"><?php echo $title;?>
        	<button class="inviteIcons" data-toggle="modal" data-target="#addMemberModal"><img src="../../shared/images/addMember.png" title="Invite Members"></button>
            <!-- google hangout button-->
            <script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
            <div id="hangoutIcon" class="hangoutIcon"></div>
            <script type="text/javascript">
                gapi.hangout.render('hangoutIcon', { 'render': 'createhangout', 'widget_size':70 });
            </script>
        </h2>
        
		<!--ADD Member Modal -->
        <!-- call self php to invite member --->
        <!---------------------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------------------->     
        <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Invite A Member</h4>
              </div>
              <div class="modal-body">
               <form method="post" action="">
               			<input type="hidden" name="inviteMemberButtonClicked" value="true"/>
                        <input type="hidden" name="projectId" value="<?php echo $id[0];?>"/>
                        <input type="hidden" name="projectTitle" value="<?php echo $title;?>"/>
                    <p>
                        <label for="email">Email: </label>
                        <input type="email" name="emailToInvite" id="email" required />
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
            <h3>Derick's To Do List for <?php echo $title;?></h3> <!--TODO: Project 1 & Derick should be a db pull-->
            <table id="datatables" class="display">
            <thead>
                <tr>
                    <th>Deadline</th>
                    <th>Task</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$stmt2 = mysqli_query($connection,"SELECT * FROM tasks WHERE assignedTo = 'Derick' AND project_id = $id[0]"); /*TODO: this shouldn't be Derick instead it should be USER that has logged in!*/
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
                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $id[0];?>"/>
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
				$stmt = mysqli_query($connection,"SELECT * FROM tasks where project_id = $id[0]");
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
       
       <!------------------------------------ upload file --------------------------------------------->
       <!---------------------------------------------------------------------------------------------->
       <!---------------------------------------------------------------------------------------------->
       <!---------------------------------------------------------------------------------------------->
        <div class="tab-pane" id="uploads">
            <h3>Uploads</h3>
            <?php
			/**
			 * the class to upload files
			 * @author lamlu
			 */
			if(isset($_FILES['uploadedFile']) && isset($_POST['userEmail']) && isset($_POST['projectId']))
			{
				$iFile = $_FILES['uploadedFile'];
				$iEmail = $_POST['userEmail'];
				$iID = $_POST['projectId'];
				$resultArray = array();
				require_once("FileProcessor.php");
				$resultArray = FileProcessor::uploadFile($iFile,$iEmail,$iID);		
			}
			?>
            <form id='uploadForm' action="" method="post" enctype="multipart/form-data">
                <input type='file' id='uploadedFile' name='uploadedFile' />
                <input type="reset" value="clear" />
                <input type="hidden" value="<?php echo $id[0];?>" name="projectId" />
                <input type="hidden" value="<?php session_start(); echo $_SESSION['email'];?>" name="userEmail" />
                <input type="submit" name="submit" value="Submit" />
            </form>
  
            
        </div>
        
        <div class="tab-pane" id="forum">
            <br/>
			<!-- New Discussion Modal -->
            <div class="modal fade" id="newDiscussion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Start a New Topic</h4>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="addDiscussion.php">
                        <p>
                          <label for="topic">Topic:</label>
                          <input type="text" name="topic" id="topic" required/>
                        </p>
                        <p>
                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $id[0];?>"/>
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
          
          <div class="tabbable tabs-left">
                <ul class="nav nav-tabs ">
                	<li class="active"><a href="#default" data-toggle="tab">Discussions</a></li>
					 <?php
                        $sql = mysqli_query($connection,"SELECT * FROM discussion WHERE Project_id = $id[0] ORDER BY date DESC");
						$idArray = array();
						while($row2 = $sql->fetch_assoc()) {
							$idArray[]= $row2['id']; // storing the ids as arrays
					?>
                        <li><a href="#tab<?php echo $row2['id'];?>" data-toggle="tab" style="padding-right: 80px;"><?php echo $row2['topic'];?> </a></li>
                     <?php }/*closing while*/?>
                </ul>
                <div class="tab-content" id="postArea">
                    <div class="tab-pane active" id="default">
                      <h2>Discussion Board Manager &nbsp;&nbsp;&nbsp;<button class="icons" data-toggle="modal" data-target="#newDiscussion"><img src="../../shared/images/addTask.png" title="Start a new TOPIC"></button> </h2>
                    </div>
                    <?php 
					$count = count($idArray);
					for($x=0;$x<$count;$x++){?>
                    <div class="tab-pane" id="tab<?php echo $idArray[$x];?>">
                      <?php 
						  $sql = mysqli_query($connection,"SELECT topic FROM discussion WHERE id = $idArray[$x]");
						  $title = mysqli_fetch_row($sql);
					  ?>
                      <h2><?php echo $title[0];?></h2><button class="replyBtn" data-toggle="modal" data-target="#addPost<?php echo $idArray[$x];?>">REPLY</button>
                      <hr/>
					<!-- Add Post Modal -->
                    <div class="modal fade" id="addPost<?php echo $idArray[$x];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Add Post</h4>
                          </div>
                          <div class="modal-body">
                            <form method="post" action="addPost.php">
                                <p>
                                  <input type="hidden" name="discussionId" id="discussionId" value="<?php echo $idArray[$x];?>"/>
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
						$sql1 = mysqli_query($connection,"SELECT * FROM post WHERE Discussion_id = $idArray[$x] ORDER BY date DESC");
						$postCount = mysqli_num_rows($sql1);
							while($row2 = $sql1->fetch_assoc()) {?>
                            <div id="post" class="post">
                            	<h4>Participant says:<i id="date"><?php echo $row2['date']; ?></i></h4>
                                <?php echo $row2['msg'];?>  
                            </div> <!--/post-->
                            <?php
							}
							if($postCount == NULL){?>
								<i style="color: #FA8072">Be the first one to start the discussion</i>
							<?php }
						?>
                    </div>	
                    <?php }?>
               </div>
          </div> 
             	   
			<?php } /*closing if loop*/	?>
            
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
        
        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function (e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        });
        
        // on load of the page: switch to the currently selected tab
        var hash = window.location.hash;
        $('#tabs a[href="' + hash + '"]').tab('show');
		
    </script>    

    <?php require_once("../../shared/php/footer.php")?>


    
</body>
</html>