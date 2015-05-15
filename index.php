<?php 
//configuration for our php Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//make constant using define
define('clientID', '82db6747d1fd4a97950ce2329c23299f');
define('clientSecret', '310890515b3c4eed8aaeabd40dafdbf6');
define('redirectURI', 'http://localhost/appacademy-api/index.php');
define('ImageDirectory', 'pics/');


//Function that is going to connect to Instagram
function connectToInstagram($url){
	$ch = curl_init(); //'CH' is a curl handle returned by curl_init()
	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close($ch);
	return $result; //Can be called over and over again when we want to connect to Instagram
}

//Function to get userID since userName doesn't allow us to get pictures
function getUserID($userName){
	$url = 'https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID; //The s indicates a secure connection
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	return $results['data']['0']['id'];
}

//Function to print images onto screen
function printImages($userID){
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=5'; //Requests last 5 pics
	$instagramInfo = connectToInstagram($url); //Connecting to Instagram
	$results = json_decode($instagramInfo, true); //Creating a local variable to decode json information
	//Parse through the information one by one
	foreach ($results['data'] as $items) {
		$image_url = $items['images']['low_resolution']['url']; //Going to go through all of my results and give myself back the URL of those pictures because we want to save it in the PHP server
		echo '<img src=" '.$image_url.'"/><br/>';
		//Calling a function to save the $image_url
		savePictures($image_url);
	}
}
//Saves pics to folder
function savePictures($image_url){
	echo $image_url.'<br>'; 
	$filename = basename($image_url); //The filename is what we are storing. Basename is the built in PHP method we are using to store $image_url
	echo $filename.'<br>';
	$destination = ImageDirectory . $filename; //Making sure the image doesn't exist in the storage
	file_put_contents($destination, file_get_contents($image_url)); //Grabs image file and stores it in our server
}


if (isset($_GET['code'])){
 $code = ($_GET['code']);
 $url = 'https://api.instagram.com/oauth/access_token';
 $access_token_settings = array('client_id' => clientID,
 								'client_secret' => clientSecret,
 								'grant_type' => 'authorization_code',
 								'redirect_uri' => redirectURI,
 								'code' => $code
 								);
 //curl is what you use in php.its 
 $curl = carl_init($url);//setting a curl seesion and we put in curl because thats where we are gettting the data from
 curl_setopt($curl, CURLOPT_POST, true);
 curl_setopt($curl, CURLOPT_POSTFIELD, $access_token_settings);//setting the POSTFIELDS to the array setup that we created
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$result = curl_exec($curl); //Stores all the above information in this variable
curl_close($curl);
$results = json_decode($result, true);
$userName = $results['user']['username'];
$userID = getUserID($userName);
printImages($userID);   

}




?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Untitled</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="author" href="humans.txt">
</head>
<body>
<!-- Creating a lgoin for people to go and give approval for our web app to access their Instagram account after getting approval we are now going to hae the information so that we can play with it. -->
	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI;?>&response_type=code">LOGIN</a>
	<script src="js/main.js"></script>
</body>
</html>