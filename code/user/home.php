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
	/*	$(function() {
			$( "#addIcon" ).tooltip({
			  show: {
				effect: "slideDown",
				delay: 250
			  }
			});
		});
		$(function() {
			$( "#dialog" ).dialog({
			  autoOpen: false,
			  show: {
				effect: "blind",
				duration: 1000
			  },
			  hide: {
				effect: "explode",
				duration: 1000
			  }
			});
		 
		$( "#addIcon" ).click(function() {
		  $( "#dialog" ).dialog( "open" );
		});
	  });*/
	</script>
    <style type="text/css">
      body {
		margin-left: 20px;
		margin-top: 60px;
      }
	  #project{
		  border: 5px double #555;
		  border-radius: 20px;
		  width: 980px;
		  height: 120px;
		  margin-bottom: 10px;
		  float: left;
	  }
	  #project h2{
		  padding: 5px 0 0 20px;
		  color: #03C;
	  }
	  #project h4{
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
		  background: #FA8072 ;
		  cursor: pointer;
	  }
	  #rightDiv{
		  border: 5px double #555;
		  border-radius: 20px;
		  width: 300px;
		  height: 480px;
		  margin-left: 990px;
	  }
	  #rightDiv h3{
		  padding-left: 7px;
		  color: #03C;
	  }
/*	  button{
		  border-color:transparent;
		  background: white;
		  margin-left: 920px;
		  margin-top: 50px;
	  }*/
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
    <!-- google hangout button-->
        <!---------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------->
        <script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
    	<div id="placeholder-div2"></div>
   		<script type="text/javascript">
        	gapi.hangout.render('placeholder-div2', { 'render': 'createhangout', 'widget_size':72 });
    	</script>
        <!---------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------->
        <!---------------------------------------------------------------------------------->
<!-- Modal -->
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
			require_once("../../shared/php/DBConnection.php");
			$connection = DBConnection::connectDB();
			$stmt = mysqli_query($connection,"SELECT * FROM project"); 
			while($row = $stmt->fetch_assoc()){ 
		?>
        <div id="project" class="project" onClick="callDashboard()">
          	<h2><?=$row['name']?></h2>
            <h4><?=$row['class_num']?>: <?=$row['class_name']?></h4>
        </div>	
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
