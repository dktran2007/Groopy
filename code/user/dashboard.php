<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Groopy | Project 1</title>

<!--For Bootstrap and jQuery imports-->
    <?php require_once("../includes.php")?>
    
    <script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>
    <style type="text/css">
      body {
        padding-top: 70px;
        padding-bottom: 40px;
      }
	  #projectHeading{
		  text-align:center;
	  }
	  #tabs{
		  width: 1000px;
		  float:left;
	  }
	  #rightDiv{
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
    </style>

  </head>

  <body>

    <?php require_once("../../shared/php/navbar.php"); ?>
    <div class="container-fluid">
        <h2 id="projectHeading">Project 1</h2>
        <div id="tabs">
            <ul>
                <li><a href="#tabs-1">First</a></li>
                <li><a href="#tabs-2">Second</a></li>
                <li><a href="#tabs-3">Third</a></li>
            </ul>
            <div id="tabs-1">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
            <div id="tabs-2">Phasellus mattis tincidunt nibh. Cras orci urna, blandit id, pretium vel, aliquet ornare, felis. Maecenas scelerisque sem non nisl. Fusce sed lorem in enim dictum bibendum.</div>
            <div id="tabs-3">Nam dui erat, auctor a, dignissim quis, sollicitudin eu, felis. Pellentesque nisi urna, interdum eget, sagittis et, consequat vestibulum, lacus. Mauris porttitor ullamcorper augue.</div>
        </div> <!--/tabs-->
        <div id="rightDiv">
            <h3>ToDo list for Project 1</h3>
        </div>
      
	<?php require_once("../../shared/php/footer.php")?>
	</div> <!--/container fluid-->
    

  </body>
</html>
