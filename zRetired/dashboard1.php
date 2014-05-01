<?php 
	require_once("../../HeaderImporter.php");

	//check login
	importHeader("checklogin");
	checklogin();
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_GET['title'])) 
	{
		$title = $_GET['title'];
		$sql = mysqli_query($connection,"SELECT id FROM project WHERE name = '$title'");
		$id = mysqli_fetch_row($sql);
		//store project id to the SESSION
		if ($id[0] != NULL)
			$_SESSION['projectID'] = $id[0];
	}
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
    <link href="../../includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="../../includes/bootstrap/js/bootstrap.min.js" rel="stylesheet">

    <!-- jQuery imports -->
    <link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <script src="../../includes/jquery/groopy/js/jquery-1.10.2.js"></script>
    <script src="../../includes/jquery/groopy/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="../../includes/jquery.dataTables.js"></script>

    <!-- Page specific CSS -->
   	<link rel="stylesheet" type="text/css" href="../../shared/css/user.css">
	
	 <!-- Calendar specific jquery -->
    <link rel='stylesheet' href='../lib/cupertino/jquery-ui.min.css' />
    <link href='../../includes/calendar/fullcalendar/fullcalendar.css' rel='stylesheet' />
    <link href='../../includes/calendar/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='../../includes/calendar/lib/moment.min.js'></script>
    <script src='../../includes/calendar/lib/jquery.min.js'></script>
    <script src='../../includes/calendar/lib/jquery-ui.custom.min.js'></script>
    <script src='../../includes/calendar/fullcalendar/fullcalendar.min.js'></script>
	

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
            
             <div id="phome_nav_accountDiv" class="pdropdown">
               	<ul>
                    <li class="drop">
                        <a href="#">
                        <?php
						if(isset($_SESSION['firstName']) || isset($_SESSION['lastName']))
							echo $_SESSION['firstName']." ".$_SESSION['lastName'];
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
     </div>												<!--end main_wrapper-->
   </body>