<?php
	$inData = getRequestInfo();
	
	$firstname = $inData["firstname"];
	$lastname = $inData["lastname"];
	$login = $inData["login"];
	$password = $inData["password"];
	$email = $inData["email"];
	$phone = $inData["phone"];

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	}	
	else
	{		$validatesql = "SELECT * FROM Users WHERE Email = '" .  $email ."'";		$validateresult = $conn->query($validatesql);		$validate2sql = "SELECT * FROM Users WHERE Login = '" . $login ."'";		$validate2result = $conn->query($validate2sql);        if ($validateresult->num_rows > 0)        { 			returnWithError( "Email already in use!" );		}        elseif ($validate2result->num_rows > 0)        { 			returnWithError( "Username already in use!" );		}
		else		{		$sql = "INSERT INTO `Users`(`FirstName`, `LastName`, `Login`, `Password`, `Email`, `Phone`) VALUES ('" . $firstname . "' , '" . $lastname . "' , '" . $login . "', '" . $password . "', '" . $email . "', '" . $phone . "')";
		if( $result = $conn->query($sql) != TRUE )
		{
			returnWithError( $conn->error );
		}		returnWithError("Success!");		}
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