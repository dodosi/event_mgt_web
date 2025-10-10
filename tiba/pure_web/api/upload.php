<?php
require_once 'db_connect.php';
if(isset($_POST["submit"])) {
  $targetDir = "uploads/"; // Specify the directory where you want to store the uploaded files
  $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
  $file_name=basename($_FILES["fileToUpload"]["name"]);
  // Check if file already exists
  if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }

  // Check file size (in this example, maximum file size is set to 10MB)
  if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // If $uploadOk is set to 0, there was an error during file upload
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  } else {
    // File upload was successful, move the uploaded file to the target directory
    $sql="INSERT INTO `files`(`file_name`, `type`) VALUES('$file_name','$fileType')";
		if($connect->query($sql) === TRUE) {
				$response='ok';
				
		} else {
				
		}
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
      echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
      header("Location: ../files.php");
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
}
?>
