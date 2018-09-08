<?php
header('Content-Type: image/jpeg');
//readfile("../Test_Images/bone.jpg");

//$file = '../Test_Images/dog.jpg';
//$type = 'image/jpeg';
//header('Content-Type:'.$type);
//header('Content-Length: ' . filesize($file));




$image = exif_thumbnail('../Test_Images/sun.jpeg');
//$image = file_get_contents("../Test_Images/sun.jpeg");

  echo $image;

/*
 if ($image!==false) {
    header('Content-type: image/jpeg');
    echo $image;
    exit;
} else {
    // no thumbnail available, handle the error here
    echo 'No thumbnail available';
}








$filename = "../Test_Images/bone.jpg";
$handle = fopen($filename, "rb");
$contents = fread($handle, filesize($filename));
fclose($handle);
 
header("content-type: image/jpeg");
 
echo $contents;
*/

?>
