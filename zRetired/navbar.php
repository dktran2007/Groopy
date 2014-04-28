<?php
/********************************************
navigation bar embedded to pages
@author		:		Shruti, modified by Lam 
@date		:		04/20/14
********************************************/
require_once("../HeaderImporter.php");

//check login
importHeader("checklogin");
checklogin();
importHeader("css");
?>

<!------------------------------------------ Navigation bar starts ------------------------------------------->
<div id= "wrapper">
    <div id="pnav_box">
        <div class="pnav_logo_holder">
            <img src="http://localhost:8888/shared/assets/Groopy_Logo_32.png" />
            <span>Groopy</span>
        </div>
        
        <div class="pnav_account_holder pdropdown">
            <nav>
                <ul>
                    <li class="drop">
                        <a href="#">You</a>
                        
                        <div class="dropdownContain">
                            <div class="dropOut">
                                <div class="triangle"></div>
                                <ul>
                                    <li>Plan</li>
                                    <li>Account Settings</li>
                                    <li>Switch Account</li>
                                    <li>Sign Out</li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>							<!--end pnav_account_holder-->
    </div> 								<!--end pnav_box div-->
</div>
<!------------------------------------------ Navigation bar ends   ------------------------------------------->