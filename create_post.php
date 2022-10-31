<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "learn_and_help_db";

$connection = new mysqli($servername, $username, $password, $dbname);

if (isset($_POST['create_post'])) {
  $title = $_POST['title'];
  $author = $_POST['author'];
  $description = $_POST['description'];
  $video_link = $_POST['video_link'];
  $timestamp = date("Y-m-d");
  $fileNameArray = [];
  for($i = 0; $i < count($_FILES['file']['name']); $i++) {
    $fileName = $_FILES['file']['name'][$i];
    $fileTMP = $_FILES['file']['tmp_name'][$i];
    $fileError = $_FILES['file']['error'][$i];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    if ($fileError === 0) {
      $fileNewName = uniqid('', true).".".$fileActualExt;
      $fileDestination = 'images/blog_pictures/'.$fileNewName;
      move_uploaded_file($fileTMP, $fileDestination);
      array_push($fileNameArray, $fileDestination);
    } else {
      echo "There was an error uploading your file.";
    }
  }

  $sql = "INSERT INTO blogs VALUES (
		NULL,
		'$title',
    '$author',
    '$description',
    '$video_link',
    '$timestamp',
    '$timestamp');";

  if (!mysqli_query($connection, $sql)) {
    echo("Error description: " . mysqli_error($connection));
  } else {
    $last_id = mysqli_insert_id($connection);
    foreach($fileNameArray as $location){
      $sql = "INSERT INTO blog_pictures VALUES (
        NULL,
        '$last_id',
        '$location');";

      if (!mysqli_query($connection, $sql)) {
        echo("Error description: " . mysqli_error($connection));
      }
    }
  }
}

mysqli_close($connection);

header('Location: causes.php');
?>
