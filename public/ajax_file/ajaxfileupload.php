<html>
	<head>
		<title>Ajax File Uploader Plugin For Jquery</title>
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="ajaxfileupload.js"></script>
	<script type="text/javascript">
	function ajaxFileUpload()
	{
		$("#loading").ajaxStart(function(){
			$(this).show();
		}).ajaxComplete(function(){
			$(this).hide();
		});
		$.ajaxFileUpload({
			url:'doajaxfileupload.php',
			secureuri:false,
			fileElementId:'uploadFile',
			dataType: 'json',
			data:{name:'logan', id:'id'},
			success: function (data, status){
				if(typeof(data.error) != 'undefined'){
					if(data.error != ''){
						alert(data.error);
					}else{
						console.log(data.msg);
					}
				}
			},error: function (data, status, e){
				alert(e);
			}
		})
		return false;
	}
	</script>	
	</head>
	<body>
		<img id="loading" src="loading.gif" style="display:none;">
		<form name="form" action="" method="POST" enctype="multipart/form-data">
			<input id="uploadFile" type="file" size="45" name="image" class="input">
			<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button>
		</form>    	
	</body>
</html>