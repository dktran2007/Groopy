<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Groopy | Home</title>

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
		margin-top: 80px;
      }
	  #project{
		  border: 5px solid black;
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
		  background-color: #FFFF80;
	  }
	  #rightDiv{
		  border: 5px solid black;
		  border-radius: 20px;
		  width: 300px;
		  height: 500px;
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
    </style>

  </head>

  <body>

	<?php require_once("../../shared/php/navbar.php"); ?>
    
    <div class="container">
        <div id="project" class="project" onClick="callDashboard()">
        	<div id="floatLeft">
                <h2>Project 1</h2>
                <h4>CMPE 195B: Senior Project</h4>
            </div>
            <h4 id="percentage">50%</h4>
        </div>
        <div id="project" class="project">
          	<h2>Project 2</h2>
            <h4>Class Number: Name</h4>
        </div>		
        <div id="project" class="project">
        	<h2>Project 3</h2>
            <h4>Class Number: Name</h4>
        </div>
		<div id="rightDiv">
        	<h3>ToDo list for all projects</h3>
        </div>
	</div> <!--/container fluid-->
    <script src="../../includes/bootstrap/js/bootstrap.min.js"></script>

    <?php require_once("../../shared/php/footer.php")?>

  </body>
</html>
