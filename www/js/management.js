angular.module('displayApp', [])

    .controller('displayController', function($scope, $http, $interval) {
        var displayer = this;
		displayer.settings = [];
		displayer.assets = [];
		displayer.logs = [];
		
		displayer.mediaTable =[]
		displayer.mediaTable.sortType = 'datetime_start'; // set the default sort type 
		displayer.mediaTable.sortReverse = false; // set the default sort order 
		
		displayer.logsTable =[]
		displayer.logsTable.sortType = 'timestamp'; // set the default sort type 
		displayer.logsTable.sortReverse = false; // set the default sort order 
		
        displayer.updateTime = 10 * 60;

		displayer.editAsset =[];

/* 
##	Initialize
*/		
        displayer.update = function(){
            $http({
                method: 'GET',
                url: '../management/database.php'
            }).then(function successCallback(response) {
                console.debug(response.data);
                displayer.settings = response.data.settings;
                displayer.assets = response.data.assets;
                displayer.logs = response.data.logs;
			
			for (i=0; i<displayer.assets.length; i++) {
				
				displayer.assets[i].datetime_mod= moment(displayer.assets[i].datetime_mod).format('YYYY-MM-DD hh:mm:ss');
				displayer.assets[i].date_start = moment(displayer.assets[i].datetime_end).format('YYYY-MM-DD');
				displayer.assets[i].time_start = moment(displayer.assets[i].datetime_end).format('hh:mm:ss'); 				
				displayer.assets[i].date_end = moment(displayer.assets[i].datetime_end).format('YYYY-MM-DD');
				displayer.assets[i].time_end = moment(displayer.assets[i].datetime_end).format('hh:mm:ss');
				delete displayer.assets[i].datetime_end;
				delete displayer.assets[i].datetime_start;
				

			}
            }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
        };

		displayer.tableSortable = function() {
			$( "#sortable" ).sortable({
				placeholder: "ui-state-highlight",
				start: function(event, ui) {
					ui.item.startPos = ui.item.index()
				},
				update: function( event, ui ) {
					console.log(ui);
				}
			});
			$( "#sortable" ).disableSelection();
		};

		console.log("started");
        displayer.update();
		displayer.tableSortable();






        displayer.SQL = function(action,type,data){
            sendData={
				action:action,
				type:type,
				data:data
				};
            console.log(sendData);
            $http.post(
                './data.php',
                $.param(sendData),
                { headers : { 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;' }}
            ).then(function successCallback(response) {
            },function errorCallback(response) {
				console.debug(response);
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
			return true;
        };
		
		
/* 		
$(document).ready(function(){

    $(document).on('click', '#getUser', function(e){
  
     e.preventDefault();
  
     var uid = $(this).data('id'); // get id of clicked row
  
     $('#dynamic-content').html(''); // leave this div blank
     $('#modal-loader').show();      // load ajax loader on button click
 
     $.ajax({
          url: 'getuser.php',
          type: 'POST',
          data: 'id='+uid,
          dataType: 'html'
     })
     .done(function(data){
          console.log(data); 
          $('#dynamic-content').html(''); // blank before load.
          $('#dynamic-content').html(data); // load here
          $('#modal-loader').hide(); // hide loader  
     })
     .fail(function(){
          $('#dynamic-content').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
          $('#modal-loader').hide();
     });

    });
});		 */
		
		
	

        $scope.submitSettings = function () {
            // put your default event here
            displayer.updateSQL();
            //location.reload(true)

        };
		
        $scope.editAsset = function (index, asset) {
            // put your default event here
            
			displayer.editAsset  = asset;
			$("#editAsset").modal();
			console.log(displayer.editAsset);
        };
		
		$("#editAsset").on("hide.bs.modal", function () {
		//displayer.assets = displayer.editAsset;
			console.log(displayer.editAsset);
			//displayer.updateSQL();
            //location.reload(true)
		 });	
		
		//http://jsfiddle.net/nEWMq/88/
		$('input[name="asset_type"]').click(function () {
		//jQuery handles UI toggling correctly when we apply "data-target" attributes and call .tab('show') 
		//on the <li> elements' immediate children, e.g the <label> elements:
		$(this).closest('label').tab('show');
		console.log("click");
		});



    });

