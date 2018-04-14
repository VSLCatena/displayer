function socketData() {

	jQuery.ajax({
		type: "GET",
		url: "socket.php",
		dataType: "json",
		success: function (result) {
			
			console.log(result);
			//document.getElementById("content").innerHTML = result;
			document.getElementById('data').src = result['asset_uri'];
			
			setTimeout(function(){ socketData() }, 5000);
		},

	})

}
socketData();