<?php
/********************************************
check login
********************************************/
require_once("../../HeaderImporter.php");

//check login
importHeader("checklogin");
checklogin();
$email = $_SESSION['email'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="utf-8">
    <title>Groopy | Home</title>
    
    <?php
		importHeader("css");
		importHeader("jQuery");
	?>
    <!--Page specific css-->    
	<link rel="stylesheet" type="text/css" href="http://localhost:8888/shared/css/user.css">
	<link type="text/css" href="http://localhost:8888/includes/jquery/groopy/css/groopy/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" />
    <link href="http://localhost:8888/includes/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="http://localhost:8888/includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet" />
	<script src="../../includes/bootstrap/js/bootstrap.min.js"></script>
    
	<script type="text/javascript">
		function callDashboard(){
			window.location = "dashboard.php";
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
        <div id="phome_nav_lineDiv"> </div>
    
    <div id="phome_content">
    	<div id="phome_createProjectDiv">
            <div class="icon_content">
                <button class="icons" data-toggle="modal" data-target="#addProjModal">
                 <img src="http://localhost:8888/shared/assets/folder_icon.png" title="New Project"></button>
                 <span>Add Project</span>
             </div>
          </div>
        
          
    <!-- Add Project Modal -->
        <div class="modal fade" id="addProjModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">New Project</h4>
              </div>
              <div class="modal-body">
              <form method="post" action="addProject.php">
                <p>
                    <label for="projName">Project Name: </label>
                    <input type="text" name="projName" id="projName" required autofocus/>
                </p>
                <p>
                    <label for="projClassNum">Class Num: </label>
                    <input type="text" name="projClassNum" id="projClassNum"  />
                </p>
                <p>
                    <label for="projClassName">Class Name: </label>
                    <input type="text" name="projClassName" id="projClassName"  />
                </p>
                    <input type="hidden" name="userEmail" value="<?php echo $email;?>"/>
                <p>
                  <input type="submit" name="submit" id="submit" value="Add Project" class="btn btn-danger" />
                  <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
                </p>
                </form>
              </div>
            </div>
          </div>
        </div>
            
        <div id="phome_projectsDiv">
        	<div class="left_div">
            <?php 
                //////////////////////////////////////
                // Lam modified to filer project here
                /////////////////////////////////////
                //session_start();
                $kHomeEmail = $_SESSION['email'];
                require_once("../../shared/php/DBConnection.php");
                $connection = DBConnection::connectDB();
                $stmt = mysqli_query($connection,"SELECT p.name, p.class_name, p.class_num FROM project p, project_user pu, Users u where
                                                    u.email ='$kHomeEmail' and u.id = pu.user_id and p.id = pu.project_id and pu.active = 1"); 								
                while($row = $stmt->fetch_assoc()){ 
                
            ?>
            <a href="dashboard.php?title=<?php echo $row['name'];?>">
                <div class="project" onClick="callDashboard()">
                    <h2><?php echo $row['name'];?></h2>
                    <h4><?=$row['class_name']?></h4>
                </div>	
            </a>
            <!--h4 id="percentage">50%</h4-->	
            <?php 
                }
            ?>
            </div>							<!-- end left_div-->
            <div class="right_div">
                <h3 class="heading">Todo's List</h3>
                <?php 
	                $email = $_SESSION['email'];
                    $userSql = mysqli_query($connection, "SELECT first_name FROM users where email = '$email'");
                    $userName = mysqli_fetch_row($userSql);
                    $taskStmt = mysqli_query($connection,"SELECT t.task, t.deadline, p.name FROM tasks t, project p where 
                                                        status = 'Incomplete' AND assignedTo = '$userName[0]' AND p.id = t.project_id ORDER BY t.deadline ASC;"); 								
                    while($row = $taskStmt->fetch_assoc()){
                ?>
                <div class="displayTask">
                    <h5 id="projectName"><?php echo $row['name']?> <span id="date"><?php echo $row['deadline']?></span></h5>
                    <h6 id="taskDescription"><?php echo $row['task']?></h6>
                </div>
                <?php }
                ?>
            </div>
        </div> 									<!--/container fluid-->
		</div>									<!-- phome_content-->
	
	</div>
  </body>
</html>
