<!-- 
	Author			: Shivam Chaurasia
	Description		: Automation maillisting
	Developed for 	: Demla Productions
 -->
<!DOCTYPE html>
<html lang="en">
<head>
	<!--Jquery here start-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-alpha1/jquery.min.js"></script>
	<!-- jquery here ends -->
	<!-- Bootstrap here start-->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc= sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha256-Sk3nkD6mLTMOF0EOpNtsIry+s1CsaqQC1rVLTAy+0yc= sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>
	<!-- Bootstrap here ends -->
	<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
	<!-- custom styles -->
		<link href="<?php echo $_SERVER['REQUEST_URI']?>css/styles.css" type="text/css" rel="stylesheet"/>
	<!-- custom styles here ends -->
	
	<!-- To ensure proper rendering and touch zooming -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- Mobile first ends here -->

	<script type="text/javascript">
	$(document).ready(function(){

		$("#uploadCsv").click(function(){
			$(".csvStatus").html("");
			var data = new FormData();
			jQuery.each($('#csvFile')[0].files, function(i, file) {
				data.append('file-'+i, file);
			});
		
			jQuery.ajax({
				url: './csv.php',
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				type: 'POST',
				success: function(data){
					$(".csvStatus").html(data);
				}
			});
			
			$(".reset").click(function(){
				$(".csvStatus").html("");
			});
		});
		
		/* FUnction for loading Database*/
		$("#loadDatabase").click(function(){ 
			

			$.ajax({
				type: "POST",
				url: "loadDb.php",
				dataType : "json",
				success: function(response, textStatus, xhr) {
					console.log("success");
					console.log(response);
					$("#incsv").text(response.incsv);
					$("#indb").text(response.indb);
				},
				error: function(xhr, textStatus, errorThrown) {
					console.log("error");
				}
			});
		});
		/* Funnction for mail 
		$("#mail").click(function(){
			var data = $('#mailcontent').serialize();
		 console.log(data);
			jQuery.ajax({
				url: './mailing_list.php',
				data: data,
				cache: false,
				type: 'POST',
				contentType: false,
				processData: false,
				
				success: function(data){
					$(".mailresult").html(data);
				}
			});
			
			
		});*/
		
	});
	
	 tinymce.init({
            selector: "#emailText"
        });
		
	</script>
	
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-md-2 header-title"><h3>Bulk Mailing</h3></div>
		<div class="col-md-10"><h3></h3></div>
		
	</div>
	
	<div class="row">
		<div class="col-md-4 boxes">
		<form  enctype="multipart/form-data"  method="post">
		 
		  <div class="form-group">
			<label for="csvFile">CSV Files :</label>
			<input type="file" id="csvFile" name="csvFile">
			<p class="help-block">Input csv file here.</p>
		  </div>
		  
		  <button type="button" id="uploadCsv" class="btn btn-default">Load File</button>
		  <button type="reset"  class="btn btn-danger reset">Reset</button>
		</form>
			<span class="csvStatus label label-default"></span>
		</div>
		
		<div class="col-md-4 boxes">
			<form  method="post" id="db">
				
				<button type="button" id="loadDatabase" class="btn btn-danger">Load Database</button>
				
				<div class="row">
				<h5>
					<div class="col-md-6">
					<a href="#"> CSV  <span class="badge " id="incsv">0</span></a>
					</div>
					<div class="col-md-6">
					<a href="#"> DB  <span class="badge" id="indb">0</span></a>
					</div>
				</h5>	
				</div>
			</form>
		</div>
		
		<div class="col-md-4 boxes">
		</div>
	</div>
	
	
	<div class="row boxes">
	<form class="form-horizontal" id="mailcontent" method="POST" action="mailing_list.php">
	  <div class="form-group">
		<label for="inputEmail3" class="col-sm-1 control-label">Subject</label>
		<div class="col-sm-10">
		  <input type="text" class="form-control" id="emailSubject" name="emailSubject" placeholder="Email Subject">
		</div>
	  </div>
	  <div class="form-group">
		<label for="emailText" class="col-sm-1 control-label">Email</label>
		<div class="col-sm-10">
		  <textarea class="form-control " id="emailText" name="emailText" placeholder="Email Content" rows="7"></textarea>
		</div>
	  </div>
	  
	  <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-default" id="mail" name="mail">Mail</button>
		</div>
	  </div>
	</form>
		
	</div>
	
	
	<div class="row">
		<div class="col-md-2 ">Output </div>
		<div class="col-md-10 mailresult" ></div>
		
	</div>
</div>
</body>
</html>