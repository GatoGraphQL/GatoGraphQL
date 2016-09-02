<?php 

/**---------------------------------------------------------------------------------------------------------------
 *
 * Copied from:
 * Captcha Code Authentication Plugin
 * http://www.vinojcardoza.com/captcha-code-authentication/
 *
 * ---------------------------------------------------------------------------------------------------------------*/

require_once 'utils.php';

session_start();
//Settings: You can customize the captcha here
$image_width = 120;
$image_height = 40;
// GreenDrinks Hack 14-01-2013: added isset since otherwise produces an error in the apache log
$characters_on_image = 4;
// if (isset($_SESSION['total_no_of_characters']))
// 	$characters_on_image = $_SESSION['total_no_of_characters'];
$font = './monofont.ttf';

//The characters that can be used in the CAPTCHA code.
//avoid confusing characters (l 1 and i for example)
// GreenDrinks Hack 14-01-2013: added isset($_SESSION['captcha_type']) && in each if statement since otherwise produces an error in the apache log
if(isset($_SESSION['captcha_type']) && $_SESSION['captcha_type'] == 'alphanumeric'){
	switch($_SESSION['captcha_letters']){
		case 'capital': 
			$possible_letters = '23456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'small': 
			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			break;
		case 'capitalsmall':
			$possible_letters = '23456789bcdfghjkmnpqrstvwxyzABCEFGHJKMNPRSTVWXYZ';
			break;
		default:
			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			break;
	} 
}
elseif(isset($_SESSION['captcha_type']) && $_SESSION['captcha_type'] == 'alphabets'){
	switch($_SESSION['captcha_letters']){
		case 'capital': 
			$possible_letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'small': 
			$possible_letters = 'bcdfghjkmnpqrstvwxyz';
			break;
		case 'capitalsmall':
			$possible_letters = 'bcdfghjkmnpqrstvwxyzABCEFGHJKMNPRSTVWXYZ';
			break;
		default:
			$possible_letters = 'abcdefghijklmnopqrstuvwxyz';
			break;
	}
}
elseif(isset($_SESSION['captcha_type']) && $_SESSION['captcha_type'] == 'numbers'){
	$possible_letters = '0123456789';
}
else{
	$possible_letters = '0123456789';
}
$random_dots = 0;
$random_lines = 20;
$captcha_text_color="0x142864";
$captcha_noice_color = "0x142864";

$code = '';


$i = 0;
while ($i < $characters_on_image) { 
$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
$i++;
}


$font_size = $image_height * 0.75;
$image = @imagecreate($image_width, $image_height);


/* setting the background, text and noise colours here */
$background_color = imagecolorallocate($image, 255, 255, 255);

$arr_text_color = hexrgb($captcha_text_color);
$text_color = imagecolorallocate($image, $arr_text_color['red'], 
		$arr_text_color['green'], $arr_text_color['blue']);

$arr_noice_color = hexrgb($captcha_noice_color);
$image_noise_color = imagecolorallocate($image, $arr_noice_color['red'], 
		$arr_noice_color['green'], $arr_noice_color['blue']);


/* generating the dots randomly in background */
for( $i=0; $i<$random_dots; $i++ ) {
imagefilledellipse($image, mt_rand(0,$image_width),
 mt_rand(0,$image_height), 2, 3, $image_noise_color);
}


/* generating lines randomly in background of image */
for( $i=0; $i<$random_lines; $i++ ) {
imageline($image, mt_rand(0,$image_width), mt_rand(0,$image_height),
 mt_rand(0,$image_width), mt_rand(0,$image_height), $image_noise_color);
}


/* create a text box and add 6 letters code in it */
$textbox = imagettfbbox($font_size, 0, $font, $code); 
$x = ($image_width - $textbox[4])/2;
$y = ($image_height - $textbox[5])/2;
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code);


/* Show captcha image in the page html page */
header('Content-Type: image/jpeg');// defining the image type to be shown in browser widow
imagejpeg($image);//showing the image
imagedestroy($image);//destroying the image instance

// Hack PoP Plug-in: choose where to place the code in the SESSION
$session = isset($_REQUEST['session']) ? $_REQUEST['session'] : 'code';
GD_CaptchaUtils::set_session_code($session, $code);

function hexrgb ($hexstr)
{
  $int = hexdec($hexstr);

  return array("red" => 0xFF & ($int >> 0x10),
               "green" => 0xFF & ($int >> 0x8),
               "blue" => 0xFF & $int);
}
?>