<?php
require_once('settings.php');

#create separate file (upload to Github)


echo("<pre>");

#upload
if ( isset($_POST["submit"]) ) { // submit?
	
	
	if ($_POST["asset_type"]=="img"){
		
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







<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('./core.php');
$data = array();



###
### GET COMMANDS
###
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	
#Get tracks 
$query = $pdo->query('SELECT * FROM tracks');
foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $track) {
	$tracks[]=$track;
}

# Grab all  all enlistments
$enlistments = array();
$query = $pdo->query("SELECT * FROM `enlistments` ORDER BY `date_add` DESC");
foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
	$row['track_data']= $tracks[$row['id_track']];
    $enlistments[] = $row;
}

#lower data-usage by only sending admins/enlistment_dates/tracks at setup
if ($_GET['action']=='setup') {
	
	
	# Grab all admins
	$query = $pdo->query("SELECT * FROM admins ORDER BY `date_add` DESC");
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
	$admins[]=$row; 
	}

	# Grab all enlistment_dates
	$query = $pdo->query("SELECT * FROM enlistment_dates ORDER BY `date_start` DESC");
	foreach($query->fetchAll(PDO::FETCH_ASSOC) as $row){
		if ($row['duplicates']=='1'){$row['duplicates']=true;};
		if ($row['duplicates']=='0'){$row['duplicates']=false;};
	$enlistment_dates[]=$row; 
	}	
	
	$data['admins']=$admins;
	$data['enlistment_dates']=$enlistment_dates;
	$data['tracks']=$tracks;
}


$data['enlistments']=$enlistments;

// print("<pre>");
// print_r($data); 


echo json_encode($data);

};

###
### POST COMMANDS
###	
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

//fix counters
//SELECT `tracks`.* , COUNT(`enlistments`.`id_track`) as sum from `enlistments` INNER JOIN tracks on tracks.id=enlistments.id_track group by enlistments.id_track

//karaoke.SQL = function(action,type,data)
if ($_POST['action'] == 'add'){
	try {
		if ($_POST['type'] == 'enlistments') {
			$stmt = $pdo->prepare("INSERT INTO enlistments (id_enlistment_date, id_track, name) VALUES (:id_enlistment_date,:id_track, :name)  ;");
			$stmt->bindValue(':id_enlistment_date', $_POST['data']['id_enlistment_date'], PDO::PARAM_STR);
			$stmt->bindValue(':id_track', $_POST['data']['id_track'], PDO::PARAM_STR);
			$stmt->bindValue(':name', $_POST['data']['name'], PDO::PARAM_STR);
		}
		if ($_POST['type'] == 'enlistment_dates') {
			$_POST['data']['duplicates'] = ($_POST['data']['duplicates']=='true') ? 1 : 0;
			$stmt = $pdo->prepare("INSERT INTO enlistment_dates (title, date_start, date_end, duplicates, user_add) VALUES (:title,:date_start, :date_end, :duplicates, :user_add)  ;");
			$stmt->bindValue(':title', $_POST['data']['title'], PDO::PARAM_STR);
			$stmt->bindValue(':date_start', $_POST['data']['date_start'], PDO::PARAM_STR);
			$stmt->bindValue(':date_end', $_POST['data']['date_end'], PDO::PARAM_STR);
			$stmt->bindValue(':duplicates', (int)$_POST['data']['duplicates'], PDO::PARAM_BOOL);
			$stmt->bindValue(':user_add', $_POST['data']['user_add'], PDO::PARAM_STR);
		}	
		if ($_POST['type'] == 'admins') {
			$stmt = $pdo->prepare("INSERT INTO admins (username) VALUES (:username)  ;");
			$stmt->bindValue(':username', $_POST['data'], PDO::PARAM_STR);
		}	
		$stmt->execute();
}
	
	catch(PDOException $e){
		echo $e->getMessage();
    }
}
if ($_POST['action'] == 'update'){
	try {
		if ($_POST['type'] == 'enlistments') {
			//works but not goign to implement (YET!)
			//$stmt = $pdo->prepare("UPDATE enlistments SET (id_enlistment_date, id_track, name) VALUES (:id_enlistment_date,:id_track, :name) WHERE id=:id  ;");
			//$stmt->bindValue(':id_enlistment_date', $_POST['data']['id_enlistment_date'], PDO::PARAM_STR);
			//$stmt->bindValue(':id_track', $_POST['data']['id_track'], PDO::PARAM_STR);
			//$stmt->bindValue(':name', $_POST['data']['name'], PDO::PARAM_STR);			
		}
		if ($_POST['type'] == 'enlistment_dates') {
			$_POST['data']['duplicates'] = ($_POST['data']['duplicates']=='true') ? 1 : 0;
			$stmt = $pdo->prepare("UPDATE enlistment_dates SET title=:title, date_start=:date_start, date_end=:date_end, duplicates=:duplicates,user_add=:user_add WHERE id=:id  ;");
			$stmt->bindValue(':title', $_POST['data']['title'], PDO::PARAM_STR);
			$stmt->bindValue(':date_start', $_POST['data']['date_start'], PDO::PARAM_STR);
			$stmt->bindValue(':date_end', $_POST['data']['date_end'], PDO::PARAM_STR);
			$stmt->bindValue(':duplicates', (int)$_POST['data']['duplicates'], PDO::PARAM_BOOL);
			$stmt->bindValue(':user_add', $_POST['data']['user_add'], PDO::PARAM_STR);
			$stmt->bindValue(':id', (int)$_POST['data']['id'], PDO::PARAM_INT);
		}	
		if ($_POST['type'] == 'admins') {
			//works but not going to implement
			//$stmt = $pdo->prepare("UPDATE admins SET username=:username WHERE username = :username ;");
			//$stmt->bindValue(':username', $_POST['data']['username'], PDO::PARAM_STR);
		}
		$stmt->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
    }	
}

if ($_POST['action'] == 'delete'){
	
	try {
		if ($_POST['type']=='enlistment_dates') {
			$stmt = $pdo->prepare("DELETE FROM enlistment_dates WHERE id= :id ;"); 
			$stmt->bindValue(':id', (int)$_POST['data'], PDO::PARAM_INT);			
			}
		if ($_POST['type']=='enlistments') {
			$stmt = $pdo->prepare("DELETE FROM enlistments WHERE id= :id ;"); 
			$stmt->bindValue(':id', (int)$_POST['data'], PDO::PARAM_INT);
			}
		if ($_POST['type']=='admins') {
			$stmt = $pdo->prepare("DELETE FROM admins WHERE username= :username ;"); 
			$stmt->bindValue(':username', $_POST['data']['username'], PDO::PARAM_STR);
			}
		if ($_POST['type']=='tracks') {
			//$stmt = $pdo->prepare("DELETE FROM tracks WHERE id= :id ;"); 
			}
		$stmt->execute();
	}
	
	catch(PDOException $e){
		echo $e->getMessage();
    }

}

		/* 


    echo $stmt->rowCount() . " records UPDATED successfully";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

 */
//}
};	









 
 
?>