<?php

	$inData = getRequestInfo();
	
	$userId = $inData["userId"];
	$ofname = $inData["oldFirstName"];
	$olname = $inData["oldLastName"];
	$ophone = $inData["oldPhone"];
	$oemail = $inData["oldEmail"];
	
	$fname = $inData["firstName"];
	$lname = $inData["lastName"];
	$phone = $inData["phone"];
	$email = $inData["email"];
	

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$sql = "UPDATE `Contacts` SET `FirstName`='".$fname."',`LastName`='".$lname."',`Email`='".$email."',`Phone`='".$phone."' WHERE `UserId`='".$userId."' AND `FirstName` ='".$ofname."' AND `LastName` ='".$olname."' AND `Email` ='".$oemail."' AND `Phone`='".$ophone."'";
		if( $result = $conn->query($sql) != TRUE )
		{
			returnWithError( $conn->error );
		}
		else
		{
			returnWithError("");
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
		$retValue = '{"error": " ' . $err . ' "}';
		sendResultInfoAsJson( $retValue );
	}

?>