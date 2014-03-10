<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Groopy | Home</title>
    <!--Page specific css-->    
	<link rel="stylesheet" type="text/css" href="../../shared/css/user.css">

    <!-- Bootstrap imports -->
    <link href="../../includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    
    <!-- jQuery imports -->
    <link href="../../includes/jquery/groopy/css/groopy/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    <script src="../../includes/jquery/groopy/js/jquery-1.9.1.js"></script>
    <script src="../../includes/jquery/groopy/js/jquery-ui-1.10.3.custom.js"></script>
    
    
	<script type="text/javascript">
		function callDashboard(){
			window.location = "dashboard.php";
		}
	</script>
    <style type="text/css">
      body {
		margin-left: 20px;
		margin-top: 60px;
      }
	  #project{
		  border: 2px solid #555;
		  border-radius: 20px;
		  width: 980px;
		  height: 60px;
		  margin-bottom: 10px;
		  float: left;
	  }
	  #project h2{
		  margin-top: 0px;
		  padding-top: 5px;
		  font-size: 20px;
		  padding-left: 20px;
		  color: #F33;
	  }
	  #project h4{
		  font-size: 14px;
		  padding-left: 20px;
	  }
	  #project #floatLeft{
		  float: left;
	  }
	  #project #percentage{
		  padding-top: 25px;
		  padding-left: 830px;
		  font-size: 40px;
		  color: #00A652;
	  }
	  .project:hover{
		  background: #e6e6e6;/*#FA8072 ;*/
		  cursor: pointer;
	  }
	  #rightDiv{
		  border: 2px solid #555;
		  border-radius: 20px;
		  width: 300px;
		  height: 480px;
		  margin-left: 990px;
	  }
	  #rightDiv h3{
		  padding-left: 7px;
		  color: #03C;
	  }
	  .container{
		  margin-left: 0px;
	  }
	  .icons {
		  margin-left: 940px;
	  }
    </style>

  </head>

  <body>

	<button class="icons" data-toggle="modal" data-target="#addProjModal"><img src="../../shared/images/addProject.png" title="New Project"></button>
	<?php require_once("../../shared/php/navbar.php"); ?>
      
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
            <p>
              <input type="submit" name="submit" id="submit" value="Add Project" class="btn btn-danger" />
              <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-left: 340px;">Cancel</button>
            </p>
            </form>
          </div>
        </div>
      </div>
    </div>
	    
    <div class="container">
        <?php 
			//////////////////////////////////////
			// Lam modified to filer project here
			/////////////////////////////////////
			session_start();
			$kHomeEmail = $_SESSION['email'];
			require_once("../../shared/php/DBConnection.php");
			$connection = DBConnection::connectDB();
			$stmt = mysqli_query($connection,"SELECT p.name, p.class_name, p.class_num FROM project p, project_user pu, Users u where
												u.email ='$kHomeEmail' and u.id = pu.user_id and p.id = pu.project_id"); 
			while($row = $stmt->fetch_assoc()){ 
		?>
        <a href="dashboard.php?title=<?php echo $row['name'];?>">
	        <div id="project" class="project" onClick="callDashboard()">
                <h2><?php echo $row['name'];?></h2>
                <h4><?=$row['class_num']?>: <?=$row['class_name']?></h4>
	        </div>	
        </a>
        <!--h4 id="percentage">50%</h4-->	
        <?php 
			}
		?>
		<div id="rightDiv">
        	<h3>ToDo list for all projects</h3>
        </div>
	</div> <!--/container fluid-->

	<script src="../../includes/bootstrap/js/bootstrap.min.js"></script>
    <?php require_once("../../shared/php/footer.php")?>

  </body>
</html>
