<?php
/************************************************
PHP
************************************************/
error_reporting(E_ALL);
ini_set('display_errors', '1');

require(__DIR__ . '/../../vendor/autoload.php');
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
#https://www.lidorsystems.com/support/articles/angularjs/treegrid/tree-grid-drag-drop-multiple-rows.aspx

?>

<!doctype html>

<html lang="en" ng-app="displayApp">
	<head>
		<meta charset="utf-8">

		<title>Screenly webpage</title>
		<meta name="description" content="Screenly webpage">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">		
		<link rel="stylesheet" href="../css/styles.css">
		




	</head>

	<body ng-controller="displayController as displayer">

		<div class="navbar navbar-expand-sm navbar-light bg-light">
			<a class="navbar-brand">&nbsp</a>
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar"aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav nav-tabs " role="tablist">
					<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#management" >Management</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab"  href="#display">Display</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab"  href="#settings" >Settings</a></li>
					<li class="nav-item"><a class="nav-link" data-toggle="tab"  href="#system">System Info</a></li>
					<li class="nav-item"><a class="nav-link" href="./index.php?logout">Uitloggen</a></li>
				</ul>
			</div>    
		</div>
		
		<div class="container-fluid">
			<div class="tab-content">
				<div id="management" class="tab-pane active">
				

				<div class="card card-default">
					<div class="card " >
						<div class="card-header "><button type="button" class="btn btn-primary">First screen</button></div>
						<div class="card-body">
							<button class="btn btn-info" type="button" data-toggle="modal" data-target="#previous">Previous</button>
							<button class="btn btn-warning" type="button" data-toggle="modal" data-target="#newAsset" >New</button>
							<button class="btn btn-info" type="button" data-toggle="modal" data-target="#next" >Next</button>
						</div>
					</div>
					<div class="card" style="margin:14px">
						<div class="card-header ">Active assets</div>
						<div class="card-body">
							<div class="col-xs-12 table-responsive">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="col-xs-1"></th>
											<th class="col-xs-1">#</th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.name'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse" >Name</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_start'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">Start</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_end'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">End</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.duration'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">Duration</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_mod'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">Modify</a></th>
											<th class="col-xs-1">delete</th>
											<th class="col-xs-1">Toggle</th>
											
										</tr>
									</thead>                        
									<tbody id="sortable" class=""  >
										<tr class="ui-state-default" ng-repeat="asset in displayer.filteredAssets=(displayer.assets | filter: {is_enabled: true} | orderBy:displayer.mediaTable.sortType )  | orderBy:displayer.mediaTable.sortType:displayer.mediaTable.sortReverse" >
											<td class="col-xs-1"><input type="checkbox"></input></td>
											<td class="col-xs-1">{{ asset.play_order}}</td>
											<td class="col-xs-1">{{ asset.name }}</td>
											<td class="col-xs-1">{{ asset.date_start }} {{ asset.time_start }}</td>
											<td class="col-xs-1">{{ asset.date_end }} {{ asset.time_end }}</td>
											<td class="col-xs-1">{{ asset.duration}}</td>
											<td class="col-xs-1"><button ng-click="editAsset($index,asset)">{{ asset.datetime_mod | date : "y-MM-dd HH:mm:ss"}}</button></td>
											<td class="col-xs-1">Deactivate first</td>
											<td class="col-xs-1">
												<label class="switch">
													<input type="checkbox"  ng-model="asset.is_enabled">
														<span class="slider"></span>
												</label>
											</td>
											<br />
										</tr>
									</tbody>
								</table>
							</div></div></div>
					<div class="card card" style="margin:14px">
						<div class="card-header ">Inactive assets</div>
						<div class="card-body">
							<div class="col-xs-12 table-responsive">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="col-xs-1"></th>
											<th class="col-xs-1">#</th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.name'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse" >Name</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_start'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">Start</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_end'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">End</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.duration'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">duration</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.mediaTable.sortType = 'asset.datetime_mod'; displayer.mediaTable.sortReverse = !displayer.mediaTable.sortReverse">Modify</a></th>
											<th class="col-xs-1">delete</th>
											<th class="col-xs-1">Toggle</th>
											
										</tr>
									</thead>                        
									<tbody class=""  >
										<tr class="" ng-repeat="asset in displayer.filteredAssets=(displayer.assets | filter: {is_enabled: false} | orderBy:displayer.mediaTable.sortType ) | orderBy:displayer.mediaTable.sortType:displayer.mediaTable.sortReverse" >
											<td class="col-xs-1"><input type="checkbox"></input></td>
											<td class="col-xs-1">{{ asset.play_order }}</td>
											<td class="col-xs-1">{{ asset.name }}</td>
											<td class="col-xs-1">{{ asset.date_start }} {{ asset.time_start }}</td>
											<td class="col-xs-1">{{ asset.date_end }} {{ asset.time_end }}</td>
											<td class="col-xs-1">{{ asset.duration}}</td>
											<td class="col-xs-1"><button ng-click="editAsset($index,asset)">{{ asset.datetime_mod | date : "y-MM-dd HH:mm:ss"}}</button></td>
											<td class="col-xs-1"><button ng-click="deleteAsset($index,asset)">Delete</button></td>
											<td class="col-xs-1">
												<label class="switch">
													<input type="checkbox"  ng-model="asset.is_enabled ">
														<span class="slider"></span>
												</label>
											</td>
											<br />
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>    
					
				<div id="settings" class="tab-pane">
					<div class="card card-info">
						<div class="card-header ">Configuration A</div>
						<div class="card-body">
							<div class="input-group" ng-repeat="setting in displayer.settings">
								<span class="input-group-addon modal_input_label20">{{setting.key}}</span>
								<input type="text" class="form-control" name="msg" ng-model="setting.value">
							</div>
							<?php 
/* 							default length
							Youtube parameters
							<iframe id="ytplayer" type="text/html" width="1920" height="1080"
							src="https://www.youtube.com/embed/M7lc1UVf-VE?autoplay=1&controls=0&disablekb=1&fs=0&modestbranding=1&playsinline=1&rel=0&showinfo=0&iv_load_policy=3"
							frameborder="0" allowfullscreen> */
							?>
						</div>
					</div>
				</div>
					
				<div id="system" class="tab-pane">
					<div class="card card-info">
						<div class="card-header ">Logs</div>
						<div class="card-body">                    
							<div class="col-xs-12 table-responsive">
								<table class="table table-hover table-condensed table-striped">
									<thead>
										<tr>
											<th class="col-xs-1"><a href="#" ng-click="displayer.logsTable.sortType = 'log.timestamp'; displayer.logsTable.sortReverse = !displayer.logsTable.sortReverse" >Timestamp</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.logsTable.sortType = 'log.loglevel'; displayer.logsTable.sortReverse = !displayer.logsTable.sortReverse" >Loglevel</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.logsTable.sortType = 'log.ip'; displayer.logsTable.sortReverse = !displayer.logsTable.sortReverse">IP</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.logsTable.sortType = 'log.user'; displayer.logsTable.sortReverse = !displayer.logsTable.sortReverse">User</a></th>
											<th class="col-xs-1"><a href="#" ng-click="displayer.logsTable.sortType = 'log.message'; displayer.logsTable.sortReverse = !displayer.logsTable.sortReverse">Message</a></th>
											
										</tr>
									</thead>                        
									<tbody class=""  >
										<tr class="" ng-repeat="log in displayer.filteredLogs=(displayer.logs  | orderBy:displayer.logsTable.sortType ) | orderBy:displayer.logsTable.sortType:displayer.logsTable.sortReverse" >
											
											<td class="col-xs-1">{{ log.timestamp }}</td>
											<td class="col-xs-1">{{(log.loglevel )}}</td>
											<td class="col-xs-1">{{ log.ip }}</td>
											<td class="col-xs-2">{{ log.user }}</td>
											<td class="col-xs-2">{{ log.message }}</td>
											</td>
											<br />
										</tr>
									</tbody>
								</table>
							</div>                        
						</div>
					</div>
				</div>
				
				
			</div>
		</div>
		
		<div id="newAsset" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<form action="upload.php" method="post" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title">New asset</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body ">
							<p>Choose image or URL</p>
							<div  role="tabcard" style="width:100%;">
								<ul class="nav nav-tabs nav-justified btn-group "   role="tablist">
									<li  role="tab" class="nav-item active">
										<label class=" btn btn-secondary btn-lg" data-target="#img">
											<input class=""  type="radio" name="asset_type" value="img" checked>Image</input>
										</label>
									</li>
									<li role="tab" class="nav-item">
										<label class=" btn btn-secondary btn-lg"  data-target="#url">
											<input class="" type="radio" name="asset_type" value="url">URL</input>
										</label>
									</li>
								</ul>
								<br>
							</div>
								
							<div class="form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon ">Name</span>
								<input type="text" name="asset_name" class="form-control" placeholder="Week 27 Menu"></input>						
							</div>
								
							<div class="tab-content">
								<div id="img" role="tabcard" class="tab-pane active">
									<div class="form-control input-group modal_input_group100" >
										<span class="modal_input_label20 input-group-addon">File</span>
										<div class="form-control " style="padding-bottom:3px; padding-top:3px;"><input id="img "type="file" name="img" ></input></div>
									</div>
									
	
									
								</div>
								<div id="url" role="tabcard" class="tab-pane">
									


								<div class="form-control input-group modal_input_group100" >
									<span class="modal_input_label20 input-group-addon">URL</span>
									<input type="url" name="url" class="form-control" placeholder="https://www.example.com/image.png"></input>
								</div>										
	
									
								</div>
								<div class="form-control input-group modal_input_group100 " >
									<span class="modal_input_label20 input-group-addon ">Start</span>
									<input type="date" name="date_start" class="form-control" value="<?php echo date("Y-m-d"); ?>"></input>
									<input type="time" name="time_start" class="form-control" value="<?php echo date("H:i"); ?>"></input>
								</div>			
								
								<div class=" form-control input-group modal_input_group100" >
									<span class="modal_input_label20 input-group-addon">End</span>
									<input type="date" name="date_end" class="form-control" value="<?php echo date("Y-m-d"); ?>"></input>
									<input type="time" name="time_end" class="form-control" value="<?php echo date("H:i"); ?>"></input>
								</div>									
								
								<div class="form-control input-group modal_input_group100" >
									<span class="modal_input_label20 input-group-addon">Duration</span>
									<input type="number" name="duration" class="form-control"  value="30" placeholder="seconds"></input>
								</div>	
								
							</div>
						</div>
						<div class="modal-footer">
							<input class="btn btn-warning" type="submit" value="Toevoegen" name="submit"></input>
							<button  type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	
	
	
		<div id="editAsset" class="modal fade" role="dialog">
			<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
					<form action="upload.php" method="post" enctype="multipart/form-data">
						<div class="modal-header">
							<h4 class="modal-title">Edit asset</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body ">
							<div class="form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon ">Mimetype</span>
								<input type="text" name="mime_type" ng-value="displayer.editAsset.mimetype" class="form-control  " disabled></input>						
							</div>
								
							<div class="form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon ">Name</span>
								<input type="text" name="asset_name" placeholder="Week 27 Menu" ng-value="displayer.editAsset.name" class="form-control  " disabled></input>						
							</div>
								
							

							
								


							<div class="form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon">URL</span>
									<input type="url" name="uri"  ng-value="displayer.editAsset.uri" class="form-control  " disabled></input>
							</div>										

								
					
							<div class="form-control input-group modal_input_group100 " >
								<span class="modal_input_label20 input-group-addon ">Start</span>
								<input type="date" name="date_start" class="form-control" ng-value="displayer.editAsset.date_start"></input>
								<input type="time" name="time_start" class="form-control"ng-value="displayer.editAsset.time_start"></input>
							</div>			
							
							<div class=" form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon">End</span>
								<input type="date" name="date_end" class="form-control" ng-value="displayer.editAsset.date_end"></input>
								<input type="time" name="time_end" class="form-control" ng-value="displayer.editAsset.time_end"></input>
							</div>									
							
							<div class="form-control input-group modal_input_group100" >
								<span class="modal_input_label20 input-group-addon">Duration</span>
								<input type="number" name="duration" class="form-control"  ng-value="displayer.editAsset.duration" placeholder="seconds"></input>
							</div>	
							
							
						
						<div class="modal-footer">
							<input class="btn btn-warning" type="submit" value="Change" name="submit"></input>
							<button  type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
						</div>
					</form>
				</div>
			</div>

		</div>	
	
	
	
	


                     




		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"  crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.js" crossorigin="anonymous"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
		<script src="../js/socket.js"></script>
		<script src="../js/management.js"></script>
	</body>
</html>