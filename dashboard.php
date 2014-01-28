<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="Groopy" content="Project Management Tool targetted towards college students">

    <!-- framework -->
  
    <script src="includes/jquery/groopy/js/jquery-1.9.1.js"></script>
    <script src="includes/jquery/groopy/development-bundle/ui/jquery-ui.custom.js"></script>
    <link rel="stylesheet" href="shared/css/dashboard_style.css" />
    
	  <!-- this begins scripts for the project dashboard page-->
	  <!--------------------------------------------------->
	  <!--------------------------------------------------->
	  <!--------------------------------------------------->
      <script type = "text/javascript">
	  	$(function(){
			$("#tabsDiv").tabs();
		});
	  </script>    
</head>

<body>
	<!--div wrapper-->
	<div id="wrapper">
    	<div id="titleDiv">
        	 Project Title
        </div>
        
        <div id="leftsideDiv">
            <!-- tabs div starts here -->
      	    <div id = "tabsDiv">
                <ul>
                    <li><a href="#tab-1">Tasks</a></li>
                    <li><a href="#tab-2">Meeting</a></li>
                    <li><a href="#tab-3">Members</a></li>
                    <li><a href="#tab-4">Forum</a></li>
                </ul>
            
            <!-- icon div starts here-->
            <div id="iconDiv">
                <div class="iconContainer">
                    <button type="button" onClick="">Icon 1</button>
                </div>
                <div class="iconContainer">
                    <button type="button" onClick="">Icon 2</button>
                </div>
                <div class="iconContainer">
                    <button type="button" onClick="">Icon 3</button>
                </div>
                <div class="iconContainer">
                    <button type="button" onClick="">Icon 4</button>
                </div>
            </div>
        	
            <!-- tab div-->
            <div id="tab-1">abc</div>
            <div id="tab-2">bcd</div>
            <div id="tab-3">cde</div>
            <div id="tab-4">def</div>
        </div> <!--end tabsDiv-->
        </div>
        
        <!-- rightsideDiv-->
        <div id="rightsideDiv">
        	<div class="rightContainer">
            	member div<br /><br /><br /><br /><br />
            </div><!--end firstRightDiv-->
            
            <div class="rightContainer">
            	todo div<br /><br /><br /><br /><br />
            </div><!--end firstRightDiv-->
            
            <div class="rightContainer">
            	completed task div<br /><br /><br /><br /><br />
            </div><!--end firstRightDiv-->
             
        </div> <!-- end rightsideDiv-->
    </div><!--end wrapper div-->
</body>
</html>