<?php
 require_once('/lib/csv/parsecsv.lib.php');
 require_once('/config.php');
 

	$file= "one";		#file name of csv
	
	# create new parseCSV object.
	$csv = new parseCSV();


	#Parse '_books.csv' using automatic delimiter detection...
	$csv->auto('./uploads/'.$file.'.csv');
	//print_r($csv->data);
	
	$parent = basename(dirname($_SERVER['PHP_SELF']));
	
	
	$mysql = mysqli_connect(DBHOST, DBUSER, DBPASSWORD);
	mysqli_select_db($mysql, DBNAME);
	$result=mysqli_query($mysql,"Truncate table mailinglist");
	$result = mysqli_query($mysql, "
	 LOAD DATA  INFILE '".UPLOADS.$file.".csv' INTO TABLE mailinglist FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' (email)
	");	
// LOAD DATA  INFILE 'C://xampp//htdocs//DEMLa//uploads//one.csv' INTO TABLE mailinglist FIELDS TERMINATED BY ',' LINES TERMINATED BY '\r\n' (email)
	header('Content-Type: application/json');
	if(!$result){
		$error= mysqli_error($mysql);
		
		$data2 = array( 'dbload' => 'failed', 'error'=> $error);
		echo json_encode( $data2 );
	}
	else {
		
		$result=mysqli_query($mysql,"SELECT * FROM mailinglist");
		// Return the number of rows in result set
		$rowcount=mysqli_num_rows($result);
		$data2 = array( 'dbload' => 'success', 'indb'=> $rowcount,'incsv'=>count($csv->data)+1);
		echo json_encode( $data2 );
		
	}
  
	mysqli_close($mysql);

?>