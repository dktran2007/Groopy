<?php
/**
 * This is a static class to create the connection to database
 * @author Lam Lu
 */
class DBConnection
{
	//function to connect to database
	static function connectDB ($hostname, $database, $username, $password)
	{
		$connection = new mysqli($hostname, $username,$password, $database);
		if ($connection->connect_errno)
		{
			echo ("Failed to connect to database");
			return null;
		}
		else return $connection;
		
	}
		
	//function to close the connection
	static function closeConnection ($connection)
	{
		if ($connection != null)
			$connection->close();
	}
}


?>