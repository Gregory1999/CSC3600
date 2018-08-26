<!DOCTYPE html>
<html>
<body>

<?php
$image = "Test_Images/dog.jpg";
    ini_set('display_errors', 1);
    ini_set('exif.encode_unicode', 'UTF-8');  // To see WINXP values
    error_reporting(-1);        
    //$n = (intval($_GET['n'])) ? $_GET['n'] : 99;
    //echo "ANI_$n.jpg:<br />\n";
	

    $exif = exif_read_data("Test_Images/dog.jpg", 'ANY_TAG', true);
    echo $exif===false ? "No header data found.<br />\n" : "Image contains the following headers:<br><br />\n";

    if ($exif) {
        foreach ($exif as $key => $section) {
            if (is_array($section)) {
                foreach ($section as $name => $val) {
                    echo "$key.$name: $val<br />\n";
                }
            } else {
                echo "$key: $section<br>\n";
            }
        }
    }
	
	$test = $exif['IFD0']['Comments'];
	echo "substr(mb_convert_encoding($test,'auto','byte2le'), 0 ,-1) <br>\n";
	echo  $exif['EXIF']['DateTimeOriginal'] . "<br>\n";
	echo date("d F Y H:i:s.", filemtime($image)) . "<br>\n";
	echo date("d F Y H:i:s."). "<br>\n";
?>

</body>
</html>