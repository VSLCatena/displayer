<?php
/************************************************
PHP
************************************************/
error_reporting(E_ALL);
ini_set('display_errors', '1');
/************************************************
PHP END
************************************************/

/************************************************
Load external files and definitions
************************************************/



/************************************************
Load external files and definitions END
************************************************/

/*************************************************
## Post commands 
*************************************************/	
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

print_r($_POST);
	
	}
  

?>

<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Screenly webpage</title>
  <meta name="description" content="Screenly webpage">

  <link rel="stylesheet" href="./css/styles.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="./js/socket.js"></script>



</head>

<body>
<div id="container">
	<iframe id="data"  src="" 	></iframe>
<div>
</body>
</html>
