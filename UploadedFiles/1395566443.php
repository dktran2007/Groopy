<?php
/**
 * the class to upload files
 * @author lamlu
 */



define ("MAX_FILE_SIZE", "50000");

Class UploadFile
{
	/**
	 * static function to upload user profile
	 * @param $imageFile the _FILE['image'];
	 * @param $userEmail the user email
	 * @return no error if upload successfully, error otherwise
	 */
	public static function uploadFile($uploadedFile, $userEmail, $projectId)
	{
		
		$fileName = $uploadedFile['name'];
		$updatedResultArr = array("updated" => null, "error" =>null);
		
			
		$blacklist = array("exe");
		$ext = strtolower(UploadFile::getExt($fileName));		
		//no extension
		if($ext === FALSE)
		{
			$updatedResultArr['updated'] = "failed";
			$updatedResultArr['error'] = "File has no extension";
			return $updatedResultArr;
		}
	
		$extMatch = FALSE;
		foreach($blacklist as $value)
		{
			if (strcasecmp($ext, $value) == 0)
				$extMatch = TRUE;
		}
		unset($value);
		
		//using extension that is not allowed
		if ($extMatch === TRUE)
		{
			$updatedResultArr['updated'] = "failed";
			$updatedResultArr['error'] = "no .exe files are allowed";
			return $updatedResultArr;
		}
					
		//not allow to upload file > 50Mb
		if($File['size'] > MAX_FILE_SIZE * 1024)
		{
			$updatedResultArr['updated'] = "failed";
			$updatedResultArr['error'] = "Image size exceeded 50Mb";
			return $updatedResultArr;
		}		
		
		//give a unique new name for the file
		$newFileName = time().".".$ext;
		
		$targetPath = $_SERVER['DOCUMENT_ROOT']."/UploadedFiles/".$newImgName;	
		require_once("../shared/php/DBConnection.php");
		$connection = DBConnection::connectDB();
		if ($connection != null)
		{
			if ($stmt = $connection->prepare("select id from Users where email = ?") )
			{
				if ($stmt->bind_param("s",$userEmail))
				{
					if ($stmt->execute()) 
					{
						if ($stmt->bind_result($userId))
						{
							if(!$stmt->fetch())//got error
							{
								$updatedResultArr['updated'] = "failed";
								$updatedResultArr['error'] = "could not found the user";
								return $updatedResultArr;
							}
						}
					}
				}
			}
		}
		
		//upload the file
		if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath))
		{
			$updatedResultArr['updated'] = "passed";
			$updatedResultArr['error'] = null;
			
			if ($connection != null)
			{
				if ($stmt = $connection->prepare("Insert into files(user_id, project_id, path, alias) values (?,?,?,?)") )
				{
					if ($stmt->bind_param("ddss",$userId,$projectId,$targetPath,$fileName))
					{
						if ($stmt->execute()) 
						{
							//done query, everything ok
						}
					}
				}
			}	
			return $updatedResultArr;
		}
		else
		{
			$updatedResultArr['updated'] = "failed";
			$updatedResultArr['error'] = 'Cannot move file to target path';
			return $updatedResultArr;
		}
		
	}

	//read the extension of the file
	public static function getExt($str)
	{
		$pos = strrpos($str, ".");
		if($pos === FALSE)
			return FALSE;
		$len = strlen($str) - $pos;
		$ext = substr($str, $pos + 1, $len);
		return $ext;
	}
}

if( isset($_POST['userEmail']) && isset($_POST['projectId']))
{
	//$iFile = $_FILES['uploadedFile'];
	$iEmail = $_POST['userEmail'];
	$iID = $_POST['projectId'];
	$resultArray = array();
	echo $iEmail.$iID;
	//$resultArray = UploadFile::uploadFile($iFile,$iEmail,$iID);		
}
?>