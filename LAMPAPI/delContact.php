<?php
	$inData = getRequestInfo();
	$firstname = $inData["firstname"];
	$lastname = $inData["lastname"];
	$UserId = $inData["userId"];
	$phone = $inData["phone"];
	$email = $inData["email"];

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "DELETE FROM `Contacts` WHERE `firstname` = '" . $firstname . "' AND `lastname` = '" . $lastname . "' AND `Phone` = '" . $phone . "' AND `UserId` = '" . $UserId .  "' AND `email` = '" . $email . "'";
		if($conn->query($sql) != true)
		{
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
?>