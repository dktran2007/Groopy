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
    
    <!-- Page specific CSS -->
    <link href="../../shared/css/tabs.css" rel="stylesheet">
    <style type="text/css">
		.masthead{
			margin-left: -75px;
			width: 1290px;
			border: 1px red solid;
		}
		.tab-pane{
			padding-left: 10px;
		}
	</style>
  </head>

  <body>

	<?php require_once("../../shared/php/navbar.php"); ?>

    <div class="container">
      <div class="masthead">
        <h2 class="text-muted">Project Name</h2>
        <ul id="tabs" class="nav nav-tabs nav-justified">
          <li class="active"><a href="#toDo" data-toggle="tab">To Do</a></li>
          <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
          <li><a href="#uploads" data-toggle="tab">Uploads</a></li>
          <li><a href="#forum" data-toggle="tab">Forum</a></li>
          <li><a href="#contact" data-toggle="tab">Contact</a></li>
        </ul>
        <div id="tab-content" class="tab-content">
        <div class="tab-pane active" id="toDo">
            <h3>To Do</h3>
            <p>red red red red red red</p>
        </div>
        <div class="tab-pane" id="tasks">
            <h3>Tasks</h3>
            <p>orange orange orange orange orange</p>
        </div>
        <div class="tab-pane" id="uploads">
            <h3>Uploads</h3>
            <p>yellow yellow yellow yellow yellow</p>
        </div>
        <div class="tab-pane" id="forum">
            <h3>Forum</h3>
            <p>green green green green green</p>
        </div>
        <div class="tab-pane" id="contact">
            <h3>Contact</h3>
            <p>blue blue blue blue blue</p>
        </div>
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