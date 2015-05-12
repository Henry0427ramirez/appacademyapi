<?php 
//configuration for our php Server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

//make constant using define
define{'clientID', '82db6747d1fd4a97950ce2329c23299f'};
define{'client_Secret', '310890515b3c4eed8aaeabd40dafdbf6'};
define{'redirectURL', 'http://localhost/instagram-api/index.php'};
define{'ImageDirectory', 'pics/'};
?>

<!-- CLIENT INFO
CLIENT ID	82db6747d1fd4a97950ce2329c23299f
CLIENT SECRET	310890515b3c4eed8aaeabd40dafdbf6
WEBSITE URL	http://localhost:8888/instagram-api/index.php
REDIRECT URI	http://localhost:8888/instagram-api/index.php -->

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
	<a href="https:api.instagram/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURL;?>&response_type=code">LOGIN</a>
	<script src="js/main.js"></script>
</body>
</html>