<style type="text/css">
	.navbar{
		padding: 10px 0 5px 40px;
		background: #e6e6e6;
	}
	.logo{
		font-size: 20px;
		color: #000;
		font-family:Arial, Helvetica, sans-serif;
		text-decoration: none;
	}
	.logo:hover{
		text-decoration: none;
		color: #F33;
	}
	.btn-group{
		margin-left: 1065px;
	}
	.home-icons{
		margin-right: 20px;
	}
	a{
		text-decoration: none;
	}
	li
	{
		list-style-type: none;
	}
	.dropdown-menu li a:hover{
		background: #FA8072;
	}
	.dropdown-menu{
		min-width: 130px;
		right: 0px;
		left:inherit;
	}
</style>

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container-fluid">
        <a class="logo" href="../../code/user/home.php" title="LOGO">
       	  <img alt="List-icon" src="../../shared/images/icon.png"/> <strong>GROOPY</strong></a>
        <div class="btn-group">
        
        <li class="dropdown">  
        <a class="home-icons" href="#settings" title="Change Settings"><img src="../../shared/images/settings.png"/></a>
            <a href="#profile" class="dropdown-toggle" id="icons" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="false"title="User"><img src="../../shared/images/user.png"/>
            </a>
            <ul class="dropdown-menu">
                <li><a tabindex="-1" href="#">My Account</a></li>
                <li><a tabindex="-1" href="#">Logout</a></li>
            </ul>
        </li>  
          <!--button class="btn dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" >Username <b class="caret"></b></button-->
          <ul class="dropdown-menu">
            <li><a href="#">Profile</a></li>
            <li><a href="#">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>