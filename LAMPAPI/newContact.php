<?php
	$inData = getRequestInfo();
	$UserId = $inData["UserId"];
	$firstname = $inData["firstname"];
	$lastname = $inData["lastname"];
	$email = $inData["email"];
	$phone = $inData["phone"];

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "INSERT INTO `Contacts`( `UserId`, `FirstName`, `LastName`, `Email`, `Phone`) VALUES ('" . $UserId . "', '" . $firstname . "' , '" . $lastname . "' , '" . $email . "', '" . $phone . "')";
		if( $result = $conn->query($sql) != TRUE )
		{
			returnWithError( $conn->error );
		}
		else
		{
			returnWithSuccess( $firstname, $lastname);
		}
		$conn->close();
	}
	#returnWithError( $err );
	
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
	
	function returnWithSuccess( $firstName, $lastName )
	{
		$retValue = '{"firstName":"' . $firstName . '","lastName":"' . $lastName . '"}';
		sendResultInfoAsJson( $retValue );
	}
?>