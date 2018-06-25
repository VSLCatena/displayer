<?php
require_once('settings.php');

#create separate file (upload to Github)


echo("<pre>");

#php execute
# <?php  3C3F706870 
# <?=  	 3C3F3D 
# phpinfo() 706870696E666F2829
#read script binary data

print_r($_POST);
#print_r($_FILES);

$finfo = new finfo(FILEINFO_MIME_TYPE);
#upload
if ( isset($_POST["submit"]) ) { // submit?
	#prepare postdata
	$datetime_start = $_POST['date_start'] . " " . $_POST['time_start'] . ":00";
	$datetime_end = $_POST['date_end'] . " " . $_POST['time_end'] . ":00";
	if ( strlen( $_POST['asset_name'] ) >= 1 && strlen( $_POST[ 'asset_name' ] ) <= $max_len ){
		$_POST['asset_name'] = htmlentities ( trim ( $_POST['asset_name'] ) , ENT_NOQUOTES );	
	}
	else {die("no valid name");}
	
	
	if ($_POST["asset_type"]=="img"){
		unset($_POST['url']);
		if ((!empty($_FILES["img"])) && ($_FILES['img']['error'] == 0)) { //no empty & no error

	
			#get imagedata
			$_FILES['img']['pathinfo'] = pathinfo($_FILES["img"]['name']);
			$_FILES['img']['mime'] = $finfo->buffer(file_get_contents($_FILES['img']['tmp_name']));
			$_FILES['img']['imgSize'] = getimagesize($_FILES["img"]["tmp_name"]);
			$_FILES['img']['target'] = sprintf("%s%s-%s.%s", $target_dir,date("YmdHis",time()),sha1_file($_FILES['img']['tmp_name']), $_FILES['img']['pathinfo']['extension']);

			$handle = fopen($_FILES["img"]["tmp_name"], "r"); 
			$header = fread($handle, 3); #read 3 bytes of file
			fclose($handle);
			/*for ($i=0; $i<3; $i++) {
				$bytes_array[]=strtoupper(bin2hex($header[$i]));
			}
			print_r($bytes_array); */
			
			#checks
			$check['is_not_php']=!strpos($_FILES['img']['name'], 'php'); #php in filename
			$check['is_not_doubledot']=substr_count($_FILES['img']['name'], '.')==1; #double dots in filename
			$check['is_uploaded_file']=is_uploaded_file($_FILES['img']['tmp_name']); #is this file uploaded
			$check['is_img_header']=in_array(strtoupper(bin2hex($header)),$whitelist['byteheader']); #is header an image
			$check['is_correct_size'] =  $_FILES['img']['size'] <=$max_size; #is size smaller than max
			$check['is_correct_ext'] =  in_array($_FILES['img']['pathinfo']['extension'],$whitelist['ext'],true); #correct extension?
			$check['is_correct_mime'] = in_array($_FILES['img']['imgSize']['mime'],$whitelist['type'],true) && #mime1
										in_array($_FILES['img']['type'],$whitelist['type'],true) && #mime2
										in_array($_FILES['img']['mime'],$whitelist['type'],true) #mime3
										;


			
			if(	count(array_unique($check)) === 1 && move_uploaded_file($_FILES["img"]["tmp_name"], $_FILES['img']['target'])) { 
				chmod($_FILES['img']['target'],$noExecMode); //readwriteonly for owner

				#DB with composer
				
				#require('./classes.php'); when working with composer/DBAL\Configuration
	/* 			$sql = "SELECT * FROM users WHERE name = :name OR username = :name";
				$stmt = $conn->prepare($sql);
				$stmt->bindValue("name", $name);
				$stmt->execute(); 
							
				#Doctrine\DBAL
				$config = new \Doctrine\DBAL\Configuration();
				$connectionParams = array(
					'url' => 'mysql://root:adminuser@localhost/vslcatena_displayer'); 
				$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
				if(PEAR::isError($conn)) {
					unlink($_FILES['img']['target']);
					die("Error connecting to the database");
					}
				$res = $db->query("INSERT INTO assets SET name=?, uri=?, mimetype=?");
				array(
					$_POST['asset_name'], #NEEDS TO BE SANITIZED
					$_FILES['img']['target'],
					$_FILES['img']['imgSize']['mime']);
				if(PEAR::isError($res)) {
					unlink($_FILES['img']['target']);
					die("Error saving data to the database. The file was not uploaded");
					}
				$id = $db->getOne('SELECT LAST_INSERT_ID() FROM uploads'); # MySQL specific
				echo("File is valid, and was successfully uploaded. You can view it <ahref=" . $_FILES['img']['target'] . ">here</a>");
				*/
				#DB with PDO
				require_once('db_core.php');
				try {
					
					$stmt = $pdo->prepare(
					"INSERT INTO assets (name, uri, datetime_start, datetime_end, duration, mimetype, is_enabled, is_processing, nocache, play_order)
					VALUES (:name,:uri, :datetime_start, :datetime_end, :duration, :mimetype, :is_enabled, :is_processing, :nocache, :play_order)  ;");
					$stmt->bindValue(':name', $_POST['asset_name'], PDO::PARAM_STR);
					$stmt->bindValue(':uri', basename($_FILES['img']['target']), PDO::PARAM_STR);
					$stmt->bindValue(':datetime_start', $datetime_start, PDO::PARAM_STR);
					$stmt->bindValue(':datetime_end', $datetime_end, PDO::PARAM_STR);
					$stmt->bindValue(':duration',  $_POST['duration'], PDO::PARAM_STR);
					$stmt->bindValue(':mimetype',  $_FILES['img']['imgSize']['mime'], PDO::PARAM_STR);
					$stmt->bindValue(':is_enabled',  false, PDO::PARAM_INT);
					$stmt->bindValue(':is_processing',  true, PDO::PARAM_INT);
					$stmt->bindValue(':nocache',  true, PDO::PARAM_INT);
					$stmt->bindValue(':play_order',  -1, PDO::PARAM_INT);
					$stmt->execute();
					}
				catch(PDOException $e){
					echo $e->getMessage();
					}
				#DB END
			
				#imagick	
				$im = new imagick($_FILES['img']['target']);
				$imageprops = $im->getImageGeometry();
				$im->stripImage(); #remove EXIF-data which could contain malicious code like <?php phpinfo() ? >
				$im->writeImage( $_FILES['img']['target'] );
				#imagick END
				
				echo('uploaded');
				} 
				
			else { #1+ checks are negative and/or error with upload.
				unlink($_FILES['img']['tmp_name']);
				echo("Sorry, there was an error uploading your file.");
				}
			}
		else { #empty file and/or error
			echo("Return Code: " . $_FILES["img"]["error"] . "<br />");
			}
		
	}
	
	if ($_POST["asset_type"]=="url"){
		if (isset($_FILES)){ #remove file references if URL is chosen
			if ($_FILES['img']['error']==0){
				unlink($_FILES['img']['tmp_name']); 
			}
			unset($_FILES['img']); 
		}
		#DB with PDO
		require_once('db_core.php');
		try {
			
			$stmt = $pdo->prepare(
			"INSERT INTO assets (name, uri, datetime_start, datetime_end, duration, mimetype, is_enabled, is_processing, nocache, play_order)
			VALUES (:name,:uri, :datetime_start, :datetime_end, :duration, :mimetype, :is_enabled, :is_processing, :nocache, :play_order)  ;");
			$stmt->bindValue(':name', $_POST['asset_name'], PDO::PARAM_STR);
			$stmt->bindValue(':uri', $_POST['url'], PDO::PARAM_STR);
			$stmt->bindValue(':datetime_start', $datetime_start, PDO::PARAM_STR);
			$stmt->bindValue(':datetime_end', $datetime_end, PDO::PARAM_STR);
			$stmt->bindValue(':duration',  $_POST['duration'], PDO::PARAM_STR);
			$stmt->bindValue(':mimetype',  'text/plain', PDO::PARAM_STR);
			$stmt->bindValue(':is_enabled',  false, PDO::PARAM_INT);
			$stmt->bindValue(':is_processing',  true, PDO::PARAM_INT);
			$stmt->bindValue(':nocache',  true, PDO::PARAM_INT);
			$stmt->bindValue(':play_order',  -1, PDO::PARAM_INT);
			$stmt->execute();
			}
		catch(PDOException $e){
			echo $e->getMessage();
			}
		#DB END
	}	
	
		
}	
		
		
	
else { //no post
	die("Is this real?");
	}
		



	



	
	
# if error then die
# if success then return to index













 
 
?>