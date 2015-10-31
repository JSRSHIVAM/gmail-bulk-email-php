<?php
 
 
$target_path = "uploads/one.csv";

 

if(move_uploaded_file($_FILES['file-0']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['file-0']['name'])." has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}

?>