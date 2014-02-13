<?php
/**
 * This is a static class to create the connection to database
 * @author Lam Lu
 * Revision 1.1: Fix the connectDB, now you can use DBConnection::connectDB() without args
 */
class DBConnection
{
	private static $connection = NULL;
	
	//initialize static variable
	private static function initialize()
	{
		self::$connection = new mysqli("localhost", "groopyuser", "groopyuser","Groopy_Schema");
	}
	//function to connect to database
	static function connectDB ()
	{
		self::initialize();
		//self::$connection = new mysqli("localhost", "Groopy_Schema", "groopyuser", "groopyuser");
		if (self::$connection->connect_errno)
		{
			echo ("Failed to connect to database");
			//printf ("\nError: %s",self::$connection->connect_error);
			return NULL;
		}
		else return self::$connection;
		
	}
		
	//function to close the connection
	static function closeConnection ()
	{
		if (self::$connection != NULL)
			self::$connection->close();
	}
}


?>