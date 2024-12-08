<?php
	$inData = getRequestInfo();
	$UserId = $inData["userId"];
	$phone = $inData["phone"];

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "DELETE FROM `Contacts` WHERE `Phone` = '".$phone."' AND `UserId` = '".$UserId. "'";
		if($conn->query($sql) === true)
		{
			returnWithSuccess();
		}
		else{
			
			returnWithError( $conn->error );
		}
		
		$conn->close();
	}
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	function returnWithSuccess( )
	{
		$retValue = '{"error":"SUCCESS"}';
		sendResultInfoAsJson( $retValue );
	}
?>