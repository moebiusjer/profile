<?
require_once("db.php");

// FOR DEBUG PURPOSES
foreach($_POST as $key=>$val) {
	echo $key.": ".$val."<br>";
}

// INIT VARS AND ESCAPE BAD CHARS
$matchID 		= str2db($_POST["matchID"]);
$gamenumber		= str2db($_POST["gamenumber"]);
$playernumber 	= str2db($_POST["playernumber"]);
$number2 		= str2db($_POST["number2"]);
$number3 		= str2db($_POST["number3"]);
$value2 		= str2db($_POST["value2"]);
$value3 		= str2db($_POST["value3"]);
$playnumber 	= str2db($_POST["playnumber"]); 
$actionid		= str2db($_POST["actionid"]);
$pointus		= str2db($_POST["pointus"]);
$pointthem		= str2db($_POST["pointthem"]);
$prettydesc		= str2db($_POST["nicedesc"]);

// INSERT INTO DB
$sql = "insert into gamedata (matchID,gamenumber,playernumber,value2,value3,number2,number3,playnumber,actionid,pointus,pointthem,prettydesc) values ('".$matchID."','".$gamenumber."','".$playernumber."','".$value2."','".$value3."','".$number2."','".$number3."','".$playnumber."','".$actionid."','".$pointus."','".$pointthem."','".$prettydesc."')";
mysqli_query($conn,$sql);

?>