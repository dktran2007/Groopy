<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Groopy | Home</title>

<!--For Bootstrap and jQuery imports-->
    <?php require_once("../includes.php")?>
    
	<script type="text/javascript">
		function callDashboard(){
			window.location = "dashboard.php";
		}
		$(function() {
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
	  });
	</script>
    <style type="text/css">
      body {
        padding-top: 70px;
        padding-bottom: 40px;
      }
	  #project{
		  margin-top: 20px;
		  border: 5px solid black;
		  border-radius: 20px;
		  width: 1000px;
		  height: 100px;
		  margin-bottom: 10px;
		  float: left;
	  }
	  #project h2{
		  padding: 10px 0 0 15px;
		  color: #03C;
	  }
	  #project h4{
		  padding-left: 10px;
	  }
	  #project #floatLeft{
		  float: left;
	  }
	  #project #percentage{
		  padding-top: 30px;
		  padding-left: 900px;
		  font-size: 40px;
		  color: #00A652;
	  }
	  .project:hover{
		  background-color: #FFFF80;
	  }
	  #rightDiv{
		  margin-top: 20px;
		  border: 5px solid black;
		  border-radius: 20px;
		  width: 300px;
		  height: 500px;
		  margin-left: 1015px;
	  }
	  #rightDiv h3{
		  padding-left: 7px;
		  color: #03C;
	  }
	  button{
		  border-color:transparent;
		  background: white;
		  margin-left: 980px;
	  }
    </style>

  </head>

  <body>
    <?php require_once("../../shared/php/navbar.php"); ?>
    <div id="dialog" title="Basic dialog">
  <p>This is an animated dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
</div>
    <button id="addIcon"><img src="../../shared/images/Icon_Add.png" title="New Project"></button>
    <div class="container-fluid">
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
        
	<?php require_once("../../shared/php/footer.php")?>
	</div> <!--/container fluid-->
    

  </body>
</html>
