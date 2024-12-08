<?php

	$inData = getRequestInfo();

	$searchResults = "";
	$searchCount = 0;
	
	$userId = $inData["userId"];
	$searchText = $inData["searchText"];

	$conn = new mysqli("localhost", "tnewman820", "Password00115!", "tnewman8_COP4331");
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	}
	else
	{
		$sql = "SELECT `FirstName`, `LastName`, `Email`, `Phone`, `DateCreated` FROM `Contacts` WHERE (`FirstName` LIKE '%" . $searchText . "%' OR `LastName` LIKE '%" . $searchText . "%' OR `Email` LIKE '%" . $searchText . "%' OR `Phone` LIKE '%" . $searchText . "%') AND `UserId` = '" . $userId . "' ORDER BY `Contacts`.`DateCreated` DESC";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_array())
			{
				if( $searchCount > 0 )
				{
					$searchResults .= ",";
				}
				$searchCount++;
				//$searchResults .= '"' . $row["FirstName"] . ' '. $row["LastName"]. " Phone Number: " . $row["Phone"] . " Email: " . $row["Email"] . '"';
				$searchResults .= '{"Id" : " '.$row["id"].' ", "firstName" : "'.$row["FirstName"].'", "lastName" : "'.$row["LastName"].'", "Phone" : "'.$row["Phone"].'", "Email" : "'.$row["Email"].'"}';
			}
			returnWithInfo( $searchResults );
		}
		
		else
		{
			returnWithError( "No Results" );
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
		$retValue = '{"results": [],"error": " ' . $err . ' "}';
		sendResultInfoAsJson( $retValue );
	}

	function returnWithInfo( $searchResults )
	{
		$retValue = '{"results":[' . $searchResults . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}

?>