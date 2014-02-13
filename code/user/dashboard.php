<?php 
	$con = mysql_connect("localhost", "root", "");
	if(!$con){
		die("Error: ".mysql_error());
	}
	mysql_select_db("groopy_schema", $con);
	$result = mysql_query("SELECT * FROM tasks");
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
		.icons{
			padding-right: 15px;
		}
	</style>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#datatables').dataTable();
		})
    </script>
  </head>

  <body>

	<?php require_once("../../shared/php/navbar.php"); ?>

    <div class="container">
      <div class="masthead">
        <h2 class="text-muted">Project Name <button class="icons" data-toggle="modal" data-target="#addMemberModal"><img src="../../shared/images/addMember.png" title="Invite Members"></button></h2>
        
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
        <br/>
        <button class="icons" data-toggle="modal" data-target="#addTaskModal"><img src="../../shared/images/addTask.png" title="Add Task"></button>
        <button class="icons" data-toggle="modal" data-target="#deleteModal"><img src="../../shared/images/delete.png" title="Delete Task"></button>
        <button class="icons" data-toggle="modal" data-target="#editModal"><img src="../../shared/images/edit.png" title="Edit Task"></button>

        <!-- Task Modal -->
        <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">New Task</h4>
              </div>
              <div class="modal-body">
                <p>
                    <label for="taskDesc">Task Description: </label>
                    <textarea rows="4" cols="29" id="taskDesc" style="border: 2px solid #CCC; border-radius: 5px;"> </textarea>
                </p>
                <p>
                    <label for="assignedTo">Assign To: </label>
                    <input type="text" id="assignedTo"  />
                </p>
                <p>
                    <label for="deadline">Deadline: </label>
                    <input type="date" id="deadline"  />
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Create</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Edit Task Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Edit Task</h4>
              </div>
              <div class="modal-body">
                <p>
                    <label for="taskDesc">Task Description: </label>
                    <textarea rows="4" cols="29" id="taskDesc" style="border: 2px solid #CCC; border-radius: 5px;"> </textarea>
                </p>
                <p>
                    <label for="assignedTo">Assign To: </label>
                    <input type="text" id="assignedTo"  />
                </p>
                <p>
                    <label for="deadline">Deadline: </label>
                    <input type="date" id="deadline"  />
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger">Edit</button>
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
                while($row = mysql_fetch_array($result)){ 
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