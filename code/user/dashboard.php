<?php 
	require_once("../../HeaderImporter.php");

	//check login
	importHeader("checklogin");
	checklogin();
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_GET['title'])) {
	$title = $_GET['title'];
	$sql = mysqli_query($connection,"SELECT id FROM project WHERE name = '$title'");
	$id = mysqli_fetch_row($sql);
	
	//store project id to the SESSION
	if ($id[0] != NULL)
		$_SESSION['projectID'] = $id[0];
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
					if ($stmt->bind_param("s", $kInviteEmail))
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Groopy | <?php echo $title;?></title>
	<?php
		importHeader("css");
	?>
	<!-- CHART.js -->
	<script src="../../includes/chart/chart.js"></script>
	
    <!-- Bootstrap imports -->
    <link href="../../includes/bootstrap/css/bootstrap.min.css" rel="stylesheet"  />
	<link href="../../includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet"  />
    <link href="../../includes/bootstrap/js/bootstrap.min.js" rel="stylesheet"  />

    <!-- jQuery imports -->
    <link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.4.custom.min.css" rel="stylesheet"  />
    <script src="../../includes/jquery/groopy/js/jquery-1.10.2.js"></script>
    <script src="../../includes/jquery/groopy/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="../../includes/jquery.dataTables.js"></script>

    <!-- Page specific CSS -->
	<link href="../../shared/css/tabs.css" rel="stylesheet"  type="text/css" >
   	<link rel="stylesheet" type="text/css" href="../../shared/css/user.css" />
	
	 <!-- Calendar specific jquery -->
    <link rel='stylesheet' href='../lib/cupertino/jquery-ui.min.css' />
    <link href='../../includes/calendar/fullcalendar/fullcalendar.css' rel='stylesheet' />
    <link href='../../includes/calendar/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='../../includes/calendar/lib/moment.min.js'></script>
    <script src='../../includes/calendar/lib/jquery.min.js'></script>
    <script src='../../includes/calendar/lib/jquery-ui.custom.min.js'></script>
    <script src='../../includes/calendar/fullcalendar/fullcalendar.min.js'></script>
	
	
    <style type="text/css">
		@import "../../shared/css/demo_table.css";
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
		  width: 920px;
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
			width: 950px;
		}
		.replyBtn{
			background: #005CE6;
			border: 1px solid black;
			padding: 2px 20px;
			  border-radius: 5px;
			  float: right;
			  margin-top: -40px;
			  margin-right: 20px;
			  color: white;
		  }
		  #taskId{
			display: flex;
			width: 500px;
			padding-left: -100px;
			font-size: 16px;
			color: #555;
		  }
		  .edit-MyTasks{
			cursor: pointer;
		  }
		  #lineChart, #doghnutChart{
		  margin-left: 80px;
		  }
	  
        /*Calendar UI*/
		  #calendarTheme {
			width: 900px;
			margin: 40px auto;
		}
		#addTask #taskError,#addTask #assignedToError, #addTask #statusError, #addTask #priorityError{
			color: red;
			display: inline;
			padding-left: 10px;
			width: 10px;
		}
		#editTask #edit-taskError,#editTask #edit-assignedToError, #editTask #edit-statusError, #editTask #edit-priorityError{
			color: red;
			display: inline;
			padding-left: 10px;
			width: 10px;
		}
	  
		/*===========================================================
								Tabs
		=============================================================*/
		#tasks, #forum, #collaborate, #calendar
		{
			background-color:#F5F5F5;
			margin-top:30px;
			padding-top: 10px;
		}
		
		/*===========================================================
								 File Tab
		=============================================================*/
		.pfile_div
		{
			position:relative;
			float:left;
			width: 100%;
			margin-top: 30px;
			background-color:#F5F5F5;
		}
		/*===========================================================
								 Vertical Nav
		=============================================================*/
		.pfile_vertical_nav
		{
			position:relative;
			float:left;
			width: 15%;
			margin-top: 10px;
			margin-left: 10px;
			margin-right: 10px;
			border:1px solid #CCC;
			background-color:#F5F5F5;
			padding: 10px;
		}
		
		.pfile_vertical_nav .buttons_div
		{
			position:relative;
			float:left;
			width: 100%;
			padding:5px;
		}
		.g_general_button
		{
			width: 100%;
			background-color:#ECECEC;
			border-radius: 3px;
			cursor:pointer;
			border:1px solid #5F5F5F;
			color:#000;
			font-size:12px;
			margin-bottom: 5px;
		}
		
		.g_general_button:hover
		{
			background-color:#CCC;
		}
		/*===========================================================
								 Groopy File
		=============================================================*/
		.pgroopyfile_div
		{
			position:relative;
			float:left;
			width: 82%;
			background-color:#F5F5F5;
		}
		.pgroopyfile_upload_div
		{
			position:relative;
			float:left;
			width: 98%;
			padding: 10px;
			border:1px solid #CCC;
			margin: 10px 0px 10px 10px;
		}
		.newlineDiv
		{
			padding-bottom:5px;
		}
		
		.submit_button
		{
			width: 100px;
			margin-top:10px;
		}
		
		.general_button
		{
			width: 70px;
			background-color:#ECECEC;
			border-radius: 3px;
			cursor:pointer;
			border:1px solid #5F5F5F;
			color:#000;
			font-size:16px;
		}
		
		.general_button:hover
		{
			background-color:#CCC;
		}
		
		.pgroopyfile_filecontainer
		{
			position:relative;
			float:left;
			width:100%;
			padding:10px;
			
		}
		.pgroopyfile_filecontainer .file_row
		{
			position:relative;
			float:left;
			width: 100%;
			height: 30px;
			padding: 4px 10px 0px 10px;
			background-color:#ECECEC;
			border:1px solid #DBDBDB;		
		}
		.pgroopyfile_filecontainer .file_row img
		{
			margin-right: 40px;
		}
		
		/*===========================================================
								 Google Drive File
		=============================================================*/
		.pgoogledrive_div
		{
			position:relative;
			float:left;
			width: 82%;
			background-color:#F5F5F5;
		}
		.pgoogledrive_filecontainer
		{
			position:relative;
			float:left;
			width: 98%;
			padding: 10px;
			border:1px solid #CCC;
			margin: 10px 0px 10px 10px;
		}
		.button_width
		{
			width: 250px;
		}
		.need_left_margin
		{
		}
	</style>
	<!--Calendar required script-->
	<script>
        $(document).ready(function() {
            $('#calendarTheme').fullCalendar({
                theme: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: new Date(),
                editable: true,
                
                events: "http://localhost:8888/code/user/connectDBJson.php",
                
                eventColor: '#E6E6E6',
                
                eventMouseover: function(event, jsEvent, view) {
                    if (event.url) {
                        $(jsEvent.target).attr('title', event.url);
                    }
                },
                
                eventClick: function(event) {
                    if (event.url) {
                        
                        return false;
                    }
                }
            });    
			
			/////////////////////////////////////////////////////////
			// Hide google drive div by default
			/////////////////////////////////////////////////////////
			
		
			$('#pgoogledrive_div').hide();
			$('#buttons_div').hide();
			$('#pgroopyfile_div').show();	
		
			 $('#googleDriveLink').click(function(){ 
				  $('#pgroopyfile_div').hide();
				  $('#pgoogledrive_div').show();
				  $('#buttons_div').show();

			  });
			
			 $('#groopyFileLink').click(function(){ 
				  $('#pgoogledrive_div').hide();
				  $('#buttons_div').hide();
				  $('#pgroopyfile_div').show();		 
			 }); 
			 
			
        });
    </script>
	
	
    <script type="text/javascript">
		$(document).ready(function(){
//			$('#datatables').dataTable(); todo table
			$('#datatables2').dataTable(); // tasks table
			$('#datatables3').dataTable(); // teams table
					
		})
		$(document).on("click", ".edit-MyTasks", function () {
			 var taskId = $(this).data('id');
			 var taskDesc = $(this).data('task');
			 var assignee = $(this).data('assignee');
			 var status = $(this).data('status');
			 var priority = $(this).data('priority');
			 var deadline = $(this).data('deadline');
			 $(".modal-body #edit-task").html( taskDesc); //to show text
			 $(".modal-body #edit-assignedTo").val(assignee); 
			 $(".modal-body #edit-status").val(status);
			 $(".modal-body #edit-priority").val(priority); 
			 $(".modal-body #edit-deadline").val(deadline); 
			 $(".modal-body #taskId").val( taskId ); // for hidden input field
		});
		
/*Before adding tasks to db, we need to check if all the fields are properly filled*/
		function validateAddTask(){
			/*Set all the error message label to blank*/
			document.getElementById("taskError").innerHTML = "";
			document.getElementById("assignedToError").innerHTML = "";
//			document.getElementById("statusError").innerHTML = "";
			document.getElementById("priorityError").innerHTML = "";
			var result = true; // all the fields are properly filled and have no error messages!
			
			/*store all the input field values*/
			var task = document.getElementById("task").value;
			var assignedTo = document.getElementById("assignedTo").selectedIndex;
//			var status = document.getElementById("status").selectedIndex;
			var priority = document.getElementById("priority").selectedIndex;
			
			/*using regular expression to check if an input field is empty*/
			var emptyString = new RegExp("^\\s*$");

			if (emptyString.test(task))
			{
				document.getElementById("taskError").innerHTML = "*";
				document.getElementById("task").focus();
				result = false;
			}
			if (assignedTo == 0){
				document.getElementById("assignedToError").innerHTML = "*";
				//addTask.assignedTo.focus();
				result = false;
			}
			else{
				document.getElementById("assignedToError").innerHTML = "";
			}
			if (priority == 0){
				document.getElementById("priorityError").innerHTML = "*";
				result = false;
			}
			else{
				document.getElementById("priorityError").innerHTML = "";
			}
			return result;
		}
    
		function validateEditTask(){
			
			/*Set all the error message label to blank*/
			document.getElementById("edit-taskError").innerHTML = "";
			document.getElementById("edit-assignedToError").innerHTML = "";
			document.getElementById("edit-statusError").innerHTML = "";
			document.getElementById("edit-priorityError").innerHTML = "";
			var result = true; // all the fields are properly filled and have no error messages!
			
			/*store all the input field values*/
			var task = document.getElementById("edit-task").value;
			var assignedTo = document.getElementById("edit-assignedTo");
			var status = document.getElementById("edit-status");
			var priority = document.getElementById("edit-priority");
			
			/*using regular expression to check if an input field is empty*/
			var emptyString = new RegExp("^\\s*$");
			
			if (emptyString.test(task))
			{
				document.getElementById("edit-taskError").innerHTML = "*";
				document.getElementById("edit-task").focus();
				result = false;
			}
			if (assignedTo.options[assignedTo.selectedIndex].value==""){
				document.getElementById("edit-assignedToError").innerHTML = "*";
				editTask.edit-assignedTo.focus();
				result = false;
			}
			else{
				document.getElementById("edit-assignedToError").innerHTML = "";
			}
			if (status.options[status.selectedIndex].value==""){
				document.getElementById("edit-statusError").innerHTML = "*";
				editTask.edit-status.focus();
				result = false;
			}
			else{
				document.getElementById("edit-statusError").innerHTML = "";
			}
			if (priority.options[priority.selectedIndex].value==""){
				document.getElementById("edit-priorityError").innerHTML = "*";
				editTask.edit-priority.focus();
				result = false;
			}
			else{
				document.getElementById("edit-priorityError").innerHTML = "";
			}
			return result;
		}
		
		function backtoHome()
		{
			window.location= "home.php";
		}
	</script>
  </head>

  <body>
	<div id="main_wrapper">
    	<div id="phome_nav">
       		<div id="phome_nav_logoDiv">
            	<img src="http://localhost:8888/shared/assets/Groopy_Logo_32.png"  onclick="backtoHome()"/>
                <span onclick="backtoHome()">Groopy</span>
                
            </div>
            <div id="phome_nav_titleDiv"><span><?php echo $title;?></span></div>
             <div id="phome_nav_accountDiv" class="pdropdown">
               	<ul>
                    <li class="drop">
                        <a href="#">
                        <?php
						if(isset($_SESSION['firstName']) || isset($_SESSION['lastName']))
						{
							$lastInitial = $_SESSION['lastName'][0];
							echo $_SESSION['firstName']." ".$lastInitial.".";
						}
                        ?></a>
                        <div class="dropdownContain">
                            <div class="dropOut">
                                <div class="triangle"></div>
                                <ul>
                                    <li><a tabindex="-1" href="signOut.php">Sign Out</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
        	</div>										<!--end phome_nav_accoutDiv-->
        </div>											<!--end phome_nav-->
     
	<?php 
		$email = $_SESSION['email'];
		$userSql = mysqli_query($connection,"SELECT first_name FROM v_user2project WHERE email = '$email'");
		$userName = mysqli_fetch_row($userSql); /*UserName is used in the todo tab*/
	?>

    <div class="container">
      <div class="masthead">
		<!--ADD Member Modal -->
        <!-- call self php to invite member --->
           
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
          <li class="active"><a href="#summary" data-toggle="tab">Summary</a></li>
          <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
          <li><a href="#uploads" data-toggle="tab">Files</a></li>
          <li><a href="#forum" data-toggle="tab">Forum</a></li>
          <li><a href="#collaborate" data-toggle="tab">Collaborate</a></li>
		  <li><a href="#calendar" data-toggle="tab">Calendar</a></li>
        </ul>
        <div id="tab-content" class="tab-content">
        
        <!-- //////////////////// SUMMARY TAB //////////////////////  -->
        <div class="tab-pane active" id="summary">
            <?php /*Total percent of tasks completed vs. incomplete*/
				$totalTasks = mysqli_query($connection,"SELECT * FROM tasks WHERE project_id = $id[0]");
				$allTaskCount = mysqli_num_rows($totalTasks);
				$completedTasks = mysqli_query($connection,"SELECT * FROM tasks WHERE project_id = $id[0] AND status = 'Complete'");
				$completedTasksCount = mysqli_num_rows($completedTasks);
				$incompletedTasksCount = $allTaskCount - $completedTasksCount;
				if($allTaskCount != 0){ /*To prevent "Dividing by ZERO error"*/
					$completed = ($completedTasksCount / $allTaskCount) * 100;
					$incomplete = 100 - $completed;
				}
				
				$teamMemberCount = mysqli_query($connection,"SELECT first_name FROM v_user2project WHERE project_id = $id[0]");
				$numberOfUsers = mysqli_num_rows($teamMemberCount);
				
				$colors = array("#953ED5","#46BFBD","#130873","#949FB1","#4D5360", "#FF7400", "85004B"); // six colors for doughnut graph
				$userArray = array(); // array to save user's completed task count
				
//				$teamMember = mysqli_fetch_row($teamMemberCount);

						
			?>
            
            <br/><br/>
			 <div id="projectStatus">
                <h4 style="padding-left: 85px;">PROJECT Status</h4>
                <br/>
                <canvas id="pieChart" height="300" width="300"></canvas> <!-- for pieChart-->
                <div id="desc">
				<?php 
					if($allTaskCount != 0){ /*If a task has been created!*/
						if ($completedTasksCount >= 1){ // atleast one task is completed
				?>
                	<p style="color: #38DF64;  font-size: 15px;"> <strong><?php echo $completedTasksCount?></strong> of <strong><?php echo $allTaskCount?></strong>  tasks are completed!</p>
                    <?php } // closing completedTasksCount if
					else{
					?>
                    <p style="color: #FA8072;  font-size: 14px;"> <strong>ZERO</strong> tasks completed!</p>
                <?php 
						}// closing else
					} // closing $allTaskCount if
					else{?>
						<i style="color: #FA8072; margin-left: -350px; font-size: 15px; text-decoration:underline;">NO TASKS</i> <i style="color: #FA8072; font-size: 15px;">has been assigned currently.Click on the "Tasks" tab to get started.</i>
					<?php }
				?>
                </div>
            </div>
            <div id="teamStatus" style="margin-right:50px;">
           		<h4 style="padding-left: 150px;">TEAM Contribution</h4>
                <br/>
                <canvas id="doghnutChart" height="300" width="300"></canvas> 
                <div id="desc2">
                <?php 
				if ($numberOfUsers > 1){ // for your project you have more than yourself!
					if ($completedTasksCount != 0){ // if teams members haven't completed their tasks?>
						<p>Team of <?php echo $numberOfUsers;?> contribution</p>
						<?php 
						$i = 0;
						
						 while ($row = $teamMemberCount->fetch_assoc() ) {
							 $user = $row['first_name'];
							 $individualContribution = mysqli_query($connection,"SELECT * FROM tasks WHERE assignedTo = '$user' AND project_id = $id[0] AND status = 'Complete'");
							$userCount = mysqli_num_rows($individualContribution);
							$userArray[$i] = $userCount;
	//						$percent = ($userArray[$i]/ $allTaskCount) * 100;
							$i++;
						?>
						<div id="user2contribution">
							<div id="user"><?php echo $user?>
								<div id="contribution"><?php echo $userCount;?></div>
							</div>
						</div>
						<?php 
						}// closing while loop 
					}// closing if
					else{?>
					<p style="color: #FA8072;  margin-left: -350px;font-size: 15px;"> Sorry, <strong>NONE</strong> of the team members have contributed!</p>
					<?php } // closing else
				}// closing if
				else{?>
                <p style="color: #FA8072;  margin-left: -350px;font-size: 15px;"> Seems like you are working alone.</p> 
                <p style="color: #FA8072;  margin-left: -350px;font-size: 15px;"> Click on the "Collaborate" tab and invite members to the project!</p>
                <?php }// closing else?>
                </div>
           </div>

			<script>
				var pieData = [
						{
							value: <?php echo $completed;?>,
							color:"#38DF64"
						},
						{
							value : <?php echo $incomplete;?>,
							color : "#F33"
						},
					];
				var myPie = new Chart(document.getElementById("pieChart").getContext("2d")).Pie(pieData);
            </script>
            <!-- for doghnutChart-->
			<script>
				var doughnutData = [
					<?php 
					$j = 0;
					while($numberOfUsers != 0){
						$percent = ($userArray[$j]/ $allTaskCount) * 100;
						$randomNum = rand(0, 5);
					?>
						{
							value: <?php echo $percent;?>,
							color: "<?php echo $colors[$randomNum];?>"
						},
					<?php 
					$numberOfUsers--;
					$j++;
					}?>
					];
			var myDoughnut = new Chart(document.getElementById("doghnutChart").getContext("2d")).Doughnut(doughnutData);
			</script>
            
            
		</div>
               
        <!-- //////////////////// TASKS TAB //////////////////////  -->
        <div class="tab-pane" id="tasks">      
        <input type="button" value="Add Task" class="blue_button submit_button" data-target="#addTaskModal" data-toggle="modal" style=" margin: 10px 20px 20px 20px;"/>
      <!--ADD Task Modal -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Assign a New Task</h4>
              </div>
              <div class="modal-body">
              
               <form method="post" action="addTask.php" id="addTask" onSubmit="return validateAddTask()">
                    <p>
                        <label for="task">Task Description: </label>
                        <textarea rows="4" cols="45" name ="task" id="task" autofocus style="border: 2px solid #CCC; border-radius: 5px;" required> </textarea>
						<label id="taskError" class = "addTaskError"></label>
                    </p>
                    <p>
                        <label for="assignedTo">Assign To: </label>
                        <?php 
						$kAssignTaskSQL = mysqli_query($connection,"SELECT first_name from v_user2project where project_id = $id[0]"); // retrieves all the members in this project
						echo '<select name="assignedTo" id="assignedTo">'; // Open your drop down box
						echo '<option value="">--Select One--</option>';
                        // Loop through the query results, outputing the options one by one
                        while ($row = $kAssignTaskSQL->fetch_assoc()) {
						   echo '<option value="'.$row['first_name'].'">'.$row['first_name'].'</option>';
                        }
                        echo '</select>';// Close your drop down box?>
						<label id="assignedToError" class = "addTaskError"></label>
                    </p>
					<p>
                        <label for="priority">Priority: </label>
                        <select name="priority" id="priority">
							<option value="">--Select One--</option>
							<option value="Low">Low</option>
							<option value="Medium">Medium</option>
							<option value="High">High</option>
						</select>
						<label id="priorityError" class="addTaskError"></label>
                    </p>
                    <p>
                       	<input type="text" value="Incomplete" name="status" id="status" hidden/>
                    </p>
					<p>
                        <label for="deadline">Deadline: </label>
                        <input type="date" name="deadline" id="deadline" required />
						<label id="deadlineError" class="addTaskError"></label>
                    </p>
                    <p>
                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $id[0];?>"/>
                    </p>
                    <p>
                      <input type="submit" name="submit" id="submit" value="Add Task"/>
                      <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                    </p>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <!--Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Task</h4>
              </div>
              <div class="modal-body">
              
               <form method="post" action="editTask.php" id="editTask" onSubmit="return validateEditTask()"> 
                    <input type="hidden" name="taskId" id="taskId" value=""/>
                    <p>
                        <label for="edit-task">Task Description: </label>
                        <textarea rows="4" cols="45" name ="edit-task" id="edit-task" style="border: 2px solid #CCC; border-radius: 5px;" autofocus required> </textarea>
						<label id="edit-taskError" class = "editTaskError"></label>
                    </p>
                    <p>
                        <label for="edit-assignedTo">Assign To: </label>
                        <?php 
						$kAssignTaskSQL = mysqli_query($connection,"SELECT first_name from v_user2project where project_id = $id[0]"); // retrieves all the members in this project
						echo '<select name="edit-assignedTo" id="edit-assignedTo">'; // Open your drop down box
						echo '<option value="">--Select One--</option>';
                        // Loop through the query results, outputing the options one by one
                        while ($row = $kAssignTaskSQL->fetch_assoc()) {
					   		echo '<option value="'.$row['first_name'].'">'.$row['first_name'].'</option>';
                        }
                        echo '</select>';// Close your drop down box?>
						<label id="edit-assignedToError" class="editTaskError"></label>
                    </p>
					<p>
                        <label for="edit-priority">Priority: </label>
                        <select name="edit-priority" id="edit-priority">
							<option value="">--Select One--</option>
							<option value="Low">Low</option>
							<option value="Medium">Medium</option>
							<option value="High">High</option>
						</select>
						<label id="edit-priorityError" class="editTaskError"></label>
                    </p>
                    <p>
                        <label for="edit-status">Status: </label>
                        <select name="edit-status" id="edit-status">
							<option value="">--Select One--</option>
							<option value="Incomplete">Incomplete</option>
							<option value="Complete">Complete</option>
						</select>
						<label id="edit-statusError" class="editTaskError"></label>
                    </p>
					<p>
                        <label for="edit-deadline">Deadline: </label>
                        <input type="date" name="edit-deadline" id="edit-deadline" required />
                    </p>
                    <p>
                        <input type="hidden" name="projectId" id="projectId" value="<?php echo $id[0];?>"/>
                    </p>
                    <p>
                      <input type="submit" name="submit" id="submit" value="Update" class="btn btn-danger" />
                      <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                    </p>
                </form>
              </div>
            </div>
          </div>
        </div>
        <table id="datatables2" class="display">
            <thead>
                <tr>
                    <th></th>
                    <th>Assigned To</th>
                    <th>Task</th>
					<th>Status</th>
					<th>Priority</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                <?php 
				$stmt = mysqli_query($connection,"SELECT * FROM tasks where project_id = $id[0]");
				$taskNumber = $row['id'];
                while($row = $stmt->fetch_assoc()){ 
                ?>
                    <tr>
                        <td><a class="edit-MyTasks" data-toggle="modal" data-target="#editTaskModal"  data-id="<?=$row['id']?>" data-task="<?=$row['task']?>" data-assignee="<?=$row['assignedTo']?>" data-status="<?=$row['status']?>" data-priority="<?=$row['priority']?>" data-deadline="<?=$row['deadline']?>">
                        		<img src="../../shared/images/edit.png" title="Edit Task <?=$row['id']?>">
                            </a></td>
                        <td><?=$row['assignedTo']?></td>
                        <td><?=$row['task']?></td>
						<td><?=$row['status']?></td>
						<td><?=$row['priority']?></td>
                        <td><?=$row['deadline']?></td>
                    </tr>
                <?php
                } // closing while loop
                ?>
            </tbody>
        </table>
		</div>
       
        <!-- //////////////////// UPLOADS TAB //////////////////////  -->
         <div class="modal fade" id="shareDrivePermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Share Folder Permission</h4>
              </div>
              <div class="modal-body">
               <form method="post" action="">
               			<input type="hidden" name="Share_Drive_Permission" value="true"/>
                    <p>
                        <label for="email">Email: </label>
                        <input type="email" name="emailToShareDrive" id="email" required />
                    </p>
                    <p>
                      <input type="submit" name="submit" id="submit" value="Share" class="btn btn-danger" />
                      <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                    </p>
                </form>
              </div>
            </div>
          </div>
        </div>
            
        <div class="tab-pane" id="uploads">
        	<div class="pfile_div">
            	<div class="pfile_vertical_nav">
            		<a href="#pgroopyfile_div" id="groopyFileLink"> Groopy Files</a>
                    <br />
                    <a href="#uploads" id="googleDriveLink">Google Drive</a>
                    <div class="buttons_div" id="buttons_div">
                   		 <form  action="" method="post" enctype="multipart/form-data">
                         	<input type="hidden" value="Create_Folder" name="Create_Folder"  />
                    		<input type="submit" value="Create Root Folder" class="g_general_button" />
                           </form>
                        <input type="button" value="Share Permission" class="g_general_button" data-toggle="modal" data-target="#shareDrivePermModal" />
                    </div>
            	</div>								<!-- end pfile_vertical_nav-->
                
                <!------------------------------------------------------------------------------------------------------------------->
                <!----------------------------------------------------pgroopyfile_div------------------------------------------------>
                <!------------------------------------------------------------------------------------------------------------------->
                <div class="pgroopyfile_div" id="pgroopyfile_div">
                    <div class="pgroopyfile_upload_div">
                        <div class="newlineDiv"><span> Choose a file to upload </span></div>
                        <span>
                            <form id='uploadForm' action="" method="post" enctype="multipart/form-data">
                                <div class="newlineDiv"><input type='file' id='uploadedFile' name='uploadedFile' /></div>
                                 <input type="reset" value="Cancel" class="general_button" />
                                 <input type="hidden" value="<?php echo $id[0];?>" name="projectId" />
                                 <input type="hidden" value="<?php session_start(); echo $_SESSION['email'];?>" name="userEmail" />
                                 <input type="submit" name="submit" value="Submit" class="blue_button submit_button"  />
                               
                            </form>
                        </span>
                    </div> 							<!-- end pgroopyfile_upload_div-->
                    
                    <div class="pgroopyfile_filecontainer">
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
                
                            $kUploadProjectID = $id[0];
                            if ($stmt = $connection->prepare("Select path, alias from files where project_id = ?")) 
                                {
                                    if ($stmt->bind_param("d", $kUploadProjectID))
                                    {
                                        if ($stmt->execute()) 
                                        {
                                            if ($stmt->bind_result($kUploadPathResult, $kUploadAliasResult))
                                            {
                                                while($stmt->fetch())
                                                {
                                                    echo '<div class="file_row">'.'<img src="http://localhost:8888/shared/assets/download_icon.png" />'.'<a href="'.$kUploadPathResult.'"download="'.$kUploadAliasResult.'">'.$kUploadAliasResult.'</a>'.'</div>';
                                                }
                                            }
                                        }
                                    }
                                }
                    
                        ?>
                        
                    </div>							<!-- end pgroopyfile_filecontainer-->
                </div>								<!-- end pgroopyfile_div-->
                
                <!------------------------------------------------------------------------------------------------------------------->
                <!----------------------------------------------------pgoogledrive_div------------------------------------------------>
                <!------------------------------------------------------------------------------------------------------------------->
                
				<?php
					if(isset($_POST['needDriveAuthen']))
					{
						require_once '../../includes/google-api-php-client/src/Google_Client.php';
						require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
					
						$client = new Google_Client();
						$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
						$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
						$client->setRedirectUri('http://localhost:8888/code/user/googleAPIAuthentication.php');
						$client->setScopes(array(
									'https://www.googleapis.com/auth/drive',
									'https://www.googleapis.com/auth/drive.file',
									'https://www.googleapis.com/auth/drive.readonly',
									'https://www.googleapis.com/auth/drive.readonly.metadata',
									'https://www.googleapis.com/auth/drive.appdata'));
						$authUrl = $client->createAuthUrl();
						if(isset($_GET['code']))
						{
							//already have access token, do something here
							$accessToken = $client->authenticate($_GET['code']);
							$email = $_SESSION['email'];
							if ($email == NULL)
							{
								//no login, redirect to login page
								header('Location: '.filter_var('../account/signIn.php'));
							}
							
							//store access token into the database
							require_once("../../shared/php/DBConnection.php");
							$connection = DBConnection::connectDB();
							if ($connection != null)
								{
									/* create a prepared statement */
									if ($stmt = $connection->prepare("Update Users set driveAPI_Token = ? where email =?")) 
									{
										//$accessToken = json_encode($accessToken);
										if ($stmt->bind_param("ss", $accessToken, $email))
										{
											if ($stmt->execute()) 
											{
												//store token ok
												
												//echo "You can use Google Drive API now";
												
											}
										}
									}
								}
							DBConnection::closeConnection();
						}
						else
						{
							//if there is no access token yet, authenticate here
							echo '<script type="text/javascript">
					
									window.open("'.$authUrl.'",\'_blank\');'.'
									</script>';
						}
					}
				?>
           
                <div class="pgoogledrive_div" id="pgoogledrive_div">
                	<div class="pgroopyfile_upload_div" id= "pgoogledrive_upload_div" style="display: none;">
                        <div class="newlineDiv"><span> Choose a file to upload to Google Drive </span></div>
                        <span>
                            <form id='driveIntegration' action="" method="post" enctype="multipart/form-data">
                                <div class="newlineDiv"><input type='file' id='uploadedToDriveFile' name='uploadedToDriveFile' /></div>
                                 <input type="reset" value="Cancel" class="general_button" />
                                 <input type="hidden" name="Upload_To_Drive" value="Upload_To_Drive" />
                                 <input type="hidden" value="<?php session_start(); echo $_SESSION['email'];?>" name="userEmail" />
                                 <input type="submit" name="submit" value="Submit" class="blue_button submit_button"  />
                               
                            </form>
                            
                        </span>
                    </div> 							<!-- end pgroopyfile_upload_div-->
                    
                	<div class="pgoogledrive_filecontainer" id="pgoogledrive_filecontainer">
                    	<span>Before you can use Google Drive service, you need to authorize Groopy to access your Google Drive</span><br />
                        <form action="" method="post">
                        	<input type="submit" class="blue_button submit_button button_width need_left_margin" value="Authorize Google Drive Access" />
                            <input type="hidden" name="needDriveAuthen" value="needDriveAuthen" />
                        </form>
                    </div>
                    <?php
					
					if(isset($_SESSION['isDriveAuthen']) && $_SESSION['isDriveAuthen'] == true)
					{
						//===================================================
						// set up Google API client and 
						// check token access for the client
						//===================================================
						require_once '../../includes/google-api-php-client/src/Google_Client.php';
						require_once '../../includes/google-api-php-client/src/contrib/Google_DriveService.php';
						require_once '../../includes/google-api-php-client/src/contrib/Google_Oauth2Service.php';
						
						$client = new Google_Client();
						$client->setClientId('509349210477-k7sfmbos0brvp7nse3ib357bq8f07krv.apps.googleusercontent.com');
						$client->setClientSecret('vOXSHACk2rfdVV8d1VMcdds0');
						$client->setRedirectUri('http://localhost:8888');
						$client->setScopes(array(
									'https://www.googleapis.com/auth/drive',
									'https://www.googleapis.com/auth/drive.file',
									'https://www.googleapis.com/auth/drive.readonly',
									'https://www.googleapis.com/auth/drive.readonly.metadata',
									'https://www.googleapis.com/auth/drive.appdata'));
						
						$refreshToken = NULL;
						//database connection
						require_once("../../shared/php/DBConnection.php");
						$connection = DBConnection::connectDB();
						
						$kEmailAuthen = $_SESSION['email'];
						if ($connection != null)
						{
							$sql = mysqli_query($connection,"Select driveAPI_Token from users where email =  '$kEmailAuthen'");
							$kdToken = mysqli_fetch_row($sql);
							//decode the string into json
							$jsonDriveAccessToken = json_decode($kdToken[0]);
							//get the refreshToken
							$refreshToken = $jsonDriveAccessToken->refresh_token;
							
							$client->setAccessToken($kdToken[0]);
							if($kdToken != NULL)
							{
								$_SESSION['isDriveAuthen'] = true;
								echo '<script type="text/javascript">
									document.getElementById("pgoogledrive_upload_div").style.display = "inline";
								</script>';
								echo '<script type="text/javascript">
									document.getElementById("pgoogledrive_filecontainer").style.display = "none";
								</script>';
								
								if($client->isAccessTokenExpired()) 
								{
									$client->refreshToken($refreshToken);
								}
								
								if ($client->getAccessToken()) 
								{
									  $kdProjectID = $_SESSION['projectID'];
									  $sql = mysqli_query($connection,"Select drive_folder_id from project where id =  '$kdProjectID'");
									  $kdfolderIDs = mysqli_fetch_row($sql);
									  $kdfolderid = $kdfolderIDs[0];
									  if($kdfolderid != NULL)
									  {
									  echo
									  '<iframe src="https://drive.google.com/embeddedfolderview?id='.$kdfolderid.'#list" width="900" height="500" frameborder="0"></iframe>';
			
									  }

							
								}
							}
							
							else $_SESSION['isDriveAuthen'] = false;
						}	
					}
					?>
           			
                    <?php
                    //===================================================
					// Upload file to google drive
					//===================================================
					if(isset($_POST['Upload_To_Drive']))
					{
						$fileMIME =  $_FILES['uploadedToDriveFile']['type'];
						$fileName = $_FILES['uploadedToDriveFile']['name'];
						
							$connection = DBConnection::connectDB();
							$projectID = $_SESSION['projectID'];
							$stmt = NULL;
							$stmt = $connection->prepare("Select drive_folder_id from project where id =?");
							$stmt->bind_param("s", $projectID);
							$stmt->execute();
							$stmt->bind_result($driveFolderID);
							$stmt->fetch();				
						if($client->isAccessTokenExpired()) 
						{
							$client->refreshToken($refreshToken);
						}
						if ($client->getAccessToken()) 
						{
								
							$service = new Google_DriveService($client);
								
							$file = new Google_DriveFile();
							$file->setTitle($fileName);
							$file->setDescription('');
							$file->setMimeType($fileMIME);
									
							$parent = new Google_ParentReference();
									
							$parent->setId($driveFolderID);
									
							$file->setParents(array($parent));
							$data = file_get_contents($_FILES['uploadedToDriveFile']['tmp_name']); 
									
							$createdFile = $service->files->insert($file, array(
								'data' => $data,
								'mimeType' => $fileMIME,
							));
						}
					}
					?>
                    
					 <?php               
                    //===================================================
                    //create the Drive Root Folder
                    //Possibly leave the subfolder creation to 
                    //Google API Picker?
                    //===================================================
                    if(isset($_POST['Create_Folder']))
                    {
                        //check if there is a root folder for the project yet?
                        //if not, create
                        //if there is already, open warning?
                        $projectID = $_SESSION['projectID'];
                        $connection = DBConnection::connectDB();
                        if ($connection != null)
                        {
                            /* create a prepared statement */
                            if ($stmt = $connection->prepare("Select name, drive_folder_id from project where id =?")) 
                            {
                                if ($stmt->bind_param("s", $projectID))
                                {
                                    if ($stmt->execute()) 
                                    { 
                                        if ($stmt->bind_result($projectName,$driveFolderID))
                                        {
                                            if($stmt->fetch())
                                            {
                                                //drive folder exists, possible warn the user to continue
                                                
                                                if (!empty($driveFolderID) && $driveFolderID != NULL )
                                                    return; //echo "there is a folder";
                                            
                                                else
                                                {
                                                    if($client->isAccessTokenExpired()) 
                                                    {
                                                        $client->refreshToken($refreshToken);
                                                    }
                                                    
                                                    //drive folder is not exist, create one
                                                    //access ok
                                                    if ($client->getAccessToken()) 
                                                    {
                                            
                                                        $service = new Google_DriveService($client);
                                                    
                                                        $file = new Google_DriveFile();
                                                        $file->setTitle($projectName.' Root Folder'); //name of the folder
                                                        $file->setDescription($projectName.' Root Folder');
                                                        $file->setMimeType('application/vnd.google-apps.folder');
                                                        $createdFile = $service->files->insert($file, array(
                                                            'mimeType' => 'application/vnd.google-apps.folder',
                                                        ));
                                                        $stmt = NULL;
                                                        $stmt = $connection->prepare("update project set drive_folder_id = ? where id = ?");
                                                        $stmt->bind_param("ss",$createdFile['id'] ,$projectID);										
                                                        $stmt->execute();
                                                      		
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
                    
                    <?php               
                    //===================================================
                    // share google drive permission
                    //===================================================
                    if(isset($_POST['Share_Drive_Permission']))
                    {
						function insertPermission($service, $fileId, $value, $type, $role) 
						{
								  $newPermission = new Google_Permission();
								  $newPermission->setValue($value);
								  $newPermission->setType($type);
								  $newPermission->setRole($role);
								  try {
									return $service->permissions->insert($fileId, $newPermission);
								  } catch (Exception $e) {
									//print "An error occurred: " . $e->getMessage();
								  }
								  return NULL;
						}
         				if($client->isAccessTokenExpired()) 
                        {
                        	$client->refreshToken($refreshToken);
						}
                                                    
                        //drive folder is not exist, create one
                        //access ok
                        if ($client->getAccessToken()) 
                        {                
							$kshareDriveEmail = $_POST['emailToShareDrive'];
                            $service = new Google_DriveService($client);
							$connection = DBConnection::connectDB();
							$projectID = $_SESSION['projectID'];
							$stmt = NULL;
							$driveFolderID = NULL;
							$stmt = $connection->prepare("Select drive_folder_id from project where id =?");
							$stmt->bind_param("s", $projectID);
							$stmt->execute();
							$stmt->bind_result($driveFolderID);
							$stmt->fetch();	
							if($driveFolderID != NULL)
								insertPermission($service,$driveFolderID,$kshareDriveEmail,"user","writer");  		
                        }            	
                    }
                    ?>
                </div>								<!-- end pgoogledrive_div-->
            </div>									<!-- end pfile_div-->
        </div>
        
        <!-- //////////////////// FORUM TAB //////////////////////  -->
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
                      <h2><?php echo $title[0];?></h2><button class="replyBtn" data-toggle="modal" data-target="#addPost<?php echo $idArray[$x];?>">POST</button>
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
                            	<input type="hidden" name="discussionId" id="discussionId" value="<?php echo $idArray[$x];?>"/>
                                <input type="hidden" name="userFirstName" id="userFirstName" value="<?php echo $userName[0];?>"/>
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
                            	<?php
									/*GET participant's name!*/
									$postById = $row2['user_id'];
									$postBySQL = mysqli_query($connection,"SELECT first_name, last_name FROM users WHERE id = $postById");
									$postBy = mysqli_fetch_row($postBySQL);
								?>
                            	<h4><?php 
								// if not the user themselves
								if(strcmp($postBy[0], $userName[0]) != 0 ){ 
									echo $postBy[0]. " " .$postBy[1];
								}
								else{
									echo "I";
								}
								?> said:<i id="date"><?php echo $row2['date']; ?></i></h4>
                                <?php echo $row2['msg'];?>  
                            </div> <!--/post-->
                            <?php
							}
							if($postCount == NULL){?> <!--//if NO new posts have been made!-->
								<i style="color: #FA8072">Be the first one to start the discussion</i>
							<?php }
						?>
                    </div>	
                    <?php }?>
               </div>
          </div> 
             	   
			<?php } /*closing if loop*/	?>
        </div>
        
        <!-- //////////////////// COLLABORATE TAB //////////////////////  -->
        <div class="tab-pane" id="collaborate">
        	<div id="pteam_buttonDiv">
            	<div class="invite_member_icon">
             <button class="inviteIcons" data-toggle="modal" data-target="#addMemberModal"><img src="../../shared/images/addMember.png" title="Invite Members"></button>
             </div>
            <!-- google hangout button-->
                <script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
                <div id="hangoutIcon" class="hangout_Icon">
                <script type="text/javascript">
                    gapi.hangout.render('hangoutIcon', { 'render': 'createhangout', 'widget_size':70 });
                </script>
                </div>
            </div>
        <br/>
            <table id="datatables3" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
           
            <tbody>
				<?php 
                    $contactSql = mysqli_query($connection,"SELECT first_name, last_name, email FROM v_user2project WHERE project_id = $id[0]");
                while($row = $contactSql->fetch_assoc()){
					// if not the user themselves
					if(strcmp($row['first_name'], $userName[0]) != 0 ){  
                ?>
                    <tr>
                        <td><?=$row['first_name']. " ". $row['last_name']?></td>
                        <td><?=$row['email']?></td>
                    </tr>
                <?php 
					}
					else{?>
                     <tr>
                        <td>ME</td>
                        <td><?=$row['email']?></td>
                    </tr>
					<?php } // closing else
					
                }	//closing while loop		
                ?>
            </tbody>
        </table>
        </div> <!--/collaborate-->
		
		<!-- //////////////////// CALENDAR TAB //////////////////////  -->
		<div class="tab-pane" id="calendar">
            <div id="calendarTheme"></div>
        </div>
    </div>
</div>
</div>												<!--end main_wrapper-->
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
	</div>
    
 
</body>
</html>