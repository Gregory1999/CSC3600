<!DOCTYPE html>
<html>
<body>

<?php
	/*
	//include the pel library
	set_include_path('scripts/pel-master' . PATH_SEPARATOR . get_include_path());
	//require_once('PelJpeg.php');
	require_once "autoload.php";
	use lsolesen\pel\PelJpeg;
	use lsolesen\pel\PelTag;
	use lsolesen\pel\PelIfd;
	*/
	$image = "../Test_Images/dog.jpg";
    ini_set('display_errors', 1);
    ini_set('exif.encode_unicode', 'UTF-8');  // To see WINXP values
	
	/*
	//update exif data
	$jpeg = new PelJpeg($image);
	$ifd0 = $jpeg->getExif()->getTiff()->getIfd();
	$entry = $ifd0->getEntry(PelTag::XP_COMMENT);
	$entry->setValue('Edited by PEL');
	$jpeg->saveFile($image);
	*/
    error_reporting(-1);        
    //$n = (intval($_GET['n'])) ? $_GET['n'] : 99;
    //echo "ANI_$n.jpg:<br />\n";
	

    $exif = exif_read_data($image, 'ANY_TAG', true);
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
	echo preg_replace("/[^A-Za-z0-9 ]/", '', $test) . "<br>\n";
	echo  $exif['EXIF']['DateTimeOriginal'] . "<br>\n";
	echo date("d F Y H:i:s.", filemtime($image)) . "<br>\n";
	echo date("d F Y H:i:s."). "<br>\n";
	
	
	//iptc data
	echo "var dump <br>\n";
	$size = getimagesize($image, $info);
	if (isset($info["APP13"])) {
		$iptc = iptcparse($info["APP13"]);
		var_dump($iptc);
		
	}
	echo "var dump <br>\n";
	
?>

</body>
</html>