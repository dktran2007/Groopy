<?php
	require_once("../../shared/php/DBConnection.php");
	$connection = DBConnection::connectDB();
	if(isset($_GET['topic'])) {
	$topic = $_GET['topic'];
	$sql = mysqli_query($connection,"SELECT id FROM discussion WHERE topic = '$topic'");
	$id = mysqli_fetch_row($sql);
	echo $topic .'</br></br>';
	$sql1 = mysqli_query($connection,"SELECT * FROM post WHERE Discussion_id = $id[0]");
		while($row2 = $sql1->fetch_assoc()) {
		  echo $row2['date'].' <br />';
		  echo $row2['msg'].'<br />';
		  echo '------------------------ <br />';
		}
	}
/**	header( 'Location: dashboard.php/#forum' ) ; // actually the posts should also show up inside the forum tab */
?>