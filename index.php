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
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => 2,
			));
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	//function to get userID cause username doesn't allow us to get picture!
	function getUserID($userName){
		$url = 'https://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.clientID;
		$instagramInfo = connectToInstagram($url);//connnecting to Instagram 
		$results = json_decode($instagramInfo, true);//creating a local variable to decode json infomation.
		return $results['data'][0]['id'];//echoing out userID.
	}
	//function to print out images onto screen
	function printImages($userID){
		$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=15';
		$instagramInfo = connectToInstagram($url);
		$results = json_decode($instagramInfo, true);
		//Parse through the info. one by one.
		foreach ($results['data'] as $items){
			$image_url = $items['images']['standard_resolution']['url'];//going to go through all of my results and give myself back the URL of those pictures because we want to save it in the PHP server.
			echo '<img src=" '.$image_url.'"/><br/>';	
			//calling a function to save that $image_url
			savePictures($image_url);	
		}
	}
//function to save image to server
	function savePictures($image_url){
		echo '<head>
					<link rel="stylesheet" href="css/style.css">
				</head>';
		echo '<body id="body-class">';
		return $image_url .'<br>';
		$filename = basename($image_url);//the filename is what we are storing. basename is the PHP built in method that we are using to store $image_url
		return $filename . '<br>';
		$destination = ImageDirectory . $filename;//making sure that the image doesn't exist in the storage.
		file_put_contents($destination, file_get_contents($image_url));//goes and grabs an imagefile and stores is into our sserver/.
	}
	if (isset($_GET['code'])){
		$code = $_GET['code'];
		$url = 'https://api.instagram.com/oauth/access_token';
		$access_token_settings = array('client_id' => clientID, 
									   'client_secret' => clientSecret,
									   'grant_type' => 'authorization_code',
									   'redirect_uri' => redirectURI,
									   'code' => $code
										);
		//cURL is what we use in PHP, it's a library calls to other API's
		$curl = curl_init($url);//setting a cURL session and we put in $url because that's where we are getting the data from.
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);//settings the POSTFIELDS to array setup that we created.
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//setting it equal to 1 because we are getting strings back.
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//but in live work-production we want to set this to true.
$result = curl_exec($curl);
curl_close($curl);
$results = json_decode($result, true);
$userName = $results['user']['username'];
$userID = getUserID($userName);
printImages($userID);
}
else{
?>
<!DOCTYPE html>
<html>
<head>
	<title>Henry's instagram</title>
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8">
	<meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/main.css">
	<link rel="author" href="human.txt">
</head>
<body>
      <a href="http://localhost/appacademyapi/index.php">HOME</a>
<!-- create a login for people to go on Instagram API. -->
<!-- creating a link to instagram through oauth/authorizing the account. -->
<!-- after setting client_id to blank in the beginning , along with redirect_uri then you have to echo it out from the constants. -->
 <a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">LOGIN</a>
 	<script src="https://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"> </script>
	<script type="js/main.js"></script>
</body>
</html>
<?php
}
?>