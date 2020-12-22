<?php
/*
Plugin Name: User Avatar (forked for PoP)
Version: 1.0
Description: User Avatar for the Platform of Platforms (PoP), forked from User Avatar plugin (http://wordpress.org/extend/plugins/user-avatar/).
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

// Hack PoP Plug-in
define ('USERAVATARPOP_FILENAME', 'useravatar_filename');


add_action('init', 'user_avatar_core_set_avatar_constants', 8 );
add_action('show_user_profile', 'user_avatar_form');

// Hack PoP Plug-in: The users cannot see the user avatar form upload from the plugin, so it is commented
// add_action('edit_user_profile', 'user_avatar_form');

add_action('wp_ajax_user_avatar_add_photo', 'user_avatar_add_photo');
add_action('user_avatar_iframe_head','user_avatar_init');


add_action('admin_print_styles-user-edit.php', 'user_avatar_admin_print_styles');
add_action('admin_print_styles-profile.php', 'user_avatar_admin_print_styles');
function user_avatar_admin_print_styles() {
	global $hook_suffix;
	wp_enqueue_script("thickbox");
	wp_enqueue_style("thickbox");
	// Hack PoP Plug-in
	// wp_enqueue_style('user-avatar', plugins_url('/user-avatar/css/user-avatar.css'), 'css');
	wp_enqueue_style('user-avatar', plugins_url('/user-avatar-popfork/css/user-avatar.css'), 'css');
}


/**
 * user_avatar_init function.
 * Description: Initializing user avatar style.
 * @access public
 * @return void
 */
function user_avatar_init(){

	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'ie' );
	// Hack PoP Plug-in
	// wp_enqueue_style('user-avatar', plugins_url('/user-avatar/css/user-avatar.css'), 'css');
	wp_enqueue_style('user-avatar', plugins_url('/user-avatar-popfork/css/user-avatar.css'), 'css');
	wp_enqueue_style('imgareaselect');
	wp_enqueue_script('imgareaselect');
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');

}
/**
 * user_avatar_core_set_avatar_constants function.
 * Description: Establishing restraints on sizes of files and dimensions of images.
 * Sets the default constants
 * @access public
 * @return void
 */
function user_avatar_core_set_avatar_constants() {

	global $bp;

	// Hack PoP Plug-in: Save the user avatar filename in the DB?
	// Added as an option, because the logic for checking for the file in the hard-drive has been also implemented,
	// yet it doesn't work as fast when hosting the avatars on AWS S3
	if (!defined('USERAVATARPOP_USEMETA')) {
		define ('USERAVATARPOP_USEMETA', true);
	}

	// Hack PoP Plug-in: Changed values for constants below
	// if ( !defined( 'USER_AVATAR_UPLOAD_PATH' ) )
	// 	define( 'USER_AVATAR_UPLOAD_PATH', user_avatar_core_avatar_upload_path() );

	// if ( !defined( 'USER_AVATAR_URL' ) )
	// 	define( 'USER_AVATAR_URL', user_avatar_core_avatar_url() );
	if ( !defined( 'USER_AVATAR_UPLOAD_PATH' ) ) {
		define( 'USER_AVATAR_UPLOAD_PATH', gd_get_avatar_upload_path());
	}
	if ( !defined( 'USER_AVATAR_URL' ) ) {
		define( 'USER_AVATAR_URL', gd_get_avatar_upload_url());
	}



	// Hack PoP Plug-in: commented because not used
	// if ( !defined( 'USER_AVATAR_THUMB_WIDTH' ) )
	// 	define( 'USER_AVATAR_THUMB_WIDTH', 50 );

	// if ( !defined( 'USER_AVATAR_THUMB_HEIGHT' ) )
	// 	define( 'USER_AVATAR_THUMB_HEIGHT', 50 );

	if ( !defined( 'USER_AVATAR_FULL_WIDTH' ) )
		define( 'USER_AVATAR_FULL_WIDTH', 150 );

	if ( !defined( 'USER_AVATAR_FULL_HEIGHT' ) )
		define( 'USER_AVATAR_FULL_HEIGHT', 150 );

	// Hack PoP Plug-in: User photo dimensions, resized from the original image without cropping
	if ( !defined( 'USER_AVATAR_PHOTO_MAXHEIGHT' ) )
		define( 'USER_AVATAR_PHOTO_MAXHEIGHT', 600 );
	if ( !defined( 'USER_AVATAR_PHOTO_MAXWIDTH' ) )
		define( 'USER_AVATAR_PHOTO_MAXWIDTH', 800 );

	if ( !defined( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE' ) ) {
		if ( !get_site_option( 'fileupload_maxk', 1500 ) )
			define( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE', 5120000 ); /* 5mb */
		else
			define( 'USER_AVATAR_ORIGINAL_MAX_FILESIZE', get_site_option( 'fileupload_maxk', 1500 ) * 1024 );
	}

	if ( !defined( 'USER_AVATAR_DEFAULT' ) )
		define( 'USER_AVATAR_DEFAULT', plugins_url('/user-avatar/images/mystery-man.jpg') );

	if ( !defined( 'USER_AVATAR_DEFAULT_THUMB' ) )
		define( 'USER_AVATAR_DEFAULT_THUMB', plugins_url('/user-avatar/images/mystery-man-50.jpg') );


	// set the language
	// Hack PoP Plug-in: comment
	// load_plugin_textdomain( 'user-avatar', false , basename( dirname( __FILE__ ) ) . '/languages' );
}

// Hack PoP Plug-in: addition of function below
function gd_get_avatar_upload_path() {

	$upload_dir = wp_upload_dir();
	$avatar_folder = $upload_dir['basedir']."/userphoto/";

	if( !file_exists($avatar_folder) ) {
		@mkdir($avatar_folder, 0777 ,true);
	}

	return $avatar_folder;
}
// Hack PoP Plug-in: addition of function below
function gd_get_avatar_upload_url() {

	$upload_dir = wp_upload_dir();
	// return $upload_dir['baseurl'] . '/avatars/';
	return $upload_dir['baseurl'] . '/userphoto/';
}

// Hack PoP Plug-in: not needed anymore
// /**
//  * user_avatar_core_avatar_upload_path function.
//  * Description: Establishing upload path/area where images that are uploaded will be stored.
//  * @access public
//  * @return void
//  */
// function user_avatar_core_avatar_upload_path()
// {
// 	if( !file_exists(WP_CONTENT_DIR."/uploads/avatars/") )
// 		mkdir(WP_CONTENT_DIR."/uploads/avatars/", 0777 ,true);

// 	return WP_CONTENT_DIR."/uploads/avatars/";
// }

// Hack PoP Plug-in: not needed anymore
// /**
//  * user_avatar_core_avatar_url function.
//  * Description: Establishing the path of the core content avatar area.
//  * @access public
//  * @return void
//  */
// function user_avatar_core_avatar_url()
// {
// 	return WP_CONTENT_URL."/uploads/avatars/";
// }

/**
 * user_avatar_add_photo function.
 * The content inside the iframe
 * Description: Creating panels for the different steps users take to upload a file and checking their uploads.
 * @access public
 * @return void
 */
function user_avatar_add_photo() {
	global $current_user;

	if((($_GET['uid'] ?? null) == $current_user->ID || current_user_can('edit_users')) && is_numeric($_GET['uid'] ?? null))
	{
		$uid = $_GET['uid'] ?? null;
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<script type="text/javascript">
//<![CDATA[
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '<?php echo SITECOOKIEPATH; ?>',
		'uid': '<?php if ( ! isset($current_user) ) $current_user = wp_get_current_user(); echo $current_user->ID; ?>',
		'time':'<?php echo time() ?>'
	},
	ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>',
	pagenow = '<?php echo $current_screen->id; ?>',
	typenow = '<?php if ( isset($current_screen->post_type) ) echo $current_screen->post_type; ?>',
	adminpage = '<?php echo $admin_body_class; ?>',
	thousandsSeparator = '<?php echo addslashes( $wp_locale->number_format['thousands_sep'] ); ?>',
	decimalPoint = '<?php echo addslashes( $wp_locale->number_format['decimal_point'] ); ?>',
	isRtl = <?php echo (int) is_rtl(); ?>;
//]]>
</script>
<?php


	do_action('user_avatar_iframe_head');


?>

</head>
<body>
<?php
	switch($_GET['step'] ?? null)
	{
		case 1:
			user_avatar_add_photo_step1($uid);
		break;

		case 2:
			user_avatar_add_photo_step2($uid);
		break;

		case 3:
			user_avatar_add_photo_step3($uid);
		break;
	}

	do_action('admin_print_footer_scripts');
?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
<?php
	}else {
		wp_die(__("You are not allowed to do that.",'user-avatar'));
	}
	die();
}

/**
 * user_avatar_add_photo_step1 function.
 * The First Step in the process
 * Description: Displays the users photo and they can choose to upload another if they please.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step1($uid)
{
	?>
	<p id="step1-image" >
	<?php
	echo user_avatar_get_avatar( $uid , 150);
	?>
	</p>
	<div id="user-avatar-step1">
	<form enctype="multipart/form-data" id="uploadForm" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=2&uid=<?php echo $uid; ?>" >
		<label for="upload"><?php _e('Choose an image from your computer:','user-avatar'); ?></label><br /><input type="file" id="upload" name="uploadedfile" />

		<?php wp_nonce_field('user-avatar') ?>
		<p class="submit"><input type="submit" value="<?php esc_attr_e('Upload'); ?>" /></p>
	</form>
	</div>

	<?php
}

/**
 * user_avatar_add_photo_step2 function.
 * The Second Step in the process
 * Description: Takes the uploaded photo and saves it to database.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step2($uid)
{


		if (!(($_FILES["uploadedfile"]["type"] == "image/gif") || ($_FILES["uploadedfile"]["type"] == "image/jpeg") || ($_FILES["uploadedfile"]["type"] == "image/png") || ($_FILES["uploadedfile"]["type"] == "image/pjpeg") || ($_FILES["uploadedfile"]["type"] == "image/x-png"))){
			echo "<div class='error'><p>".__("Please upload an image file (.jpeg, .gif, .png).",'user-avatar')."</p></div>";
			user_avatar_add_photo_step1($uid);
			die();
		}
		$overrides = array('test_form' => false);
		$file = wp_handle_upload($_FILES['uploadedfile'], $overrides);

		if ( isset($file['error']) ){
			die( $file['error'] );
		}

		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		set_transient( 'avatar_file_'.$uid, $file, 60 * 60 * 5 );
		// Construct the object array
		$object = array(
		'post_title' => $filename,
		'post_content' => $url,
		'post_mime_type' => $type,
		'guid' => $url);

		// Save the data
		list($width, $height, $type, $attr) = getimagesize( $file );

		if ( $width > 420 ) {
			$oitar = $width / 420;
			$image = wp_crop_image($file, 0, 0, $width, $height, 420, $height / $oitar, false, str_replace(basename($file), 'midsize-'.basename($file), $file));


			$url = str_replace(basename($url), basename($image), $url);
			$width = $width / $oitar;
			$height = $height / $oitar;
		} else {
			$oitar = 1;
		}

		// Hack PoP Plug-in: Allow PoP AWS to upload the file to a bucket
		do_action('user_avatar_add_photo:file-uploaded', $file, $uid);

		// Hack PoP Plug-in: Allow PoP AWS to change the domain of the URL, to point to the S3 bucket
		$url = apply_filters('user_avatar_add_photo:image-url', $url, $file, $uid);
		?>
		<form id="iframe-crop-form" method="POST" action="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=3&uid=<?php echo esc_attr($uid); ?>">

		<h4><?php _e('Choose the part of the image you want to use as your profile image.','user-avatar'); ?> <input type="submit" class="button" id="user-avatar-crop-button" value="<?php esc_attr_e('Crop Image','user-avatar'); ?>" /></h4>

		<div id="testWrap">
		<img src="<?php echo $url; ?>" id="upload" width="<?php echo esc_attr($width); ?>" height="<?php echo esc_attr($height); ?>" />
		</div>
		<div id="user-avatar-preview">
		<h4>Preview</h4>
		<div id="preview" style="width: <?php echo USER_AVATAR_FULL_WIDTH; ?>px; height: <?php echo USER_AVATAR_FULL_HEIGHT; ?>px; overflow: hidden;">
		<img src="<?php echo esc_url_raw($url); ?>" width="<?php echo esc_attr($width); ?>" height="<?php echo $height; ?>">
		</div>
		<p class="submit" >
		<input type="hidden" name="x1" id="x1" value="0" />
		<input type="hidden" name="y1" id="y1" value="0" />
		<input type="hidden" name="x2" id="x2" />
		<input type="hidden" name="y2" id="y2" />
		<input type="hidden" name="width" id="width" value="<?php echo esc_attr($width) ?>" />
		<input type="hidden" name="height" id="height" value="<?php echo esc_attr($height) ?>" />

		<input type="hidden" name="oitar" id="oitar" value="<?php echo esc_attr($oitar); ?>" />
		<?php wp_nonce_field('user-avatar'); ?>
		</p>
		</div>
		</form>

		<script type="text/javascript">

	function onEndCrop( coords ) {
		jQuery( '#x1' ).val(coords.x);
		jQuery( '#y1' ).val(coords.y);
		jQuery( '#width' ).val(coords.w);
		jQuery( '#height' ).val(coords.h);
	}

	jQuery(document).ready(function() {
		var xinit = <?php echo USER_AVATAR_FULL_WIDTH; ?>;
		var yinit = <?php echo USER_AVATAR_FULL_HEIGHT; ?>;
		var ratio = xinit / yinit;
		var ximg = jQuery('img#upload').width();
		var yimg = jQuery('img#upload').height();

		if ( yimg < yinit || ximg < xinit ) {
			if ( ximg / yimg > ratio ) {
				yinit = yimg;
				xinit = yinit * ratio;
			} else {
				xinit = ximg;
				yinit = xinit / ratio;
			}
		}

		jQuery('img#upload').imgAreaSelect({
			handles: true,
			keys: true,
			aspectRatio: xinit + ':' + yinit,
			show: true,
			x1: 0,
			y1: 0,
			x2: xinit,
			y2: yinit,
			//maxHeight: <?php echo USER_AVATAR_FULL_HEIGHT; ?>,
			//maxWidth: <?php echo USER_AVATAR_FULL_WIDTH; ?>,
			onInit: function () {
				jQuery('#width').val(xinit);
				jQuery('#height').val(yinit);
			},
			onSelectChange: function(img, c) {
				jQuery('#x1').val(c.x1);
				jQuery('#y1').val(c.y1);
				jQuery('#width').val(c.width);
				jQuery('#height').val(c.height);



				if (!c.width || !c.height)
        			return;

			    var scaleX = <?php echo USER_AVATAR_FULL_WIDTH; ?> / c.width;
			    var scaleY = <?php echo USER_AVATAR_FULL_HEIGHT; ?> / c.height;

			    jQuery('#preview img').css({
			        width: Math.round(scaleX * <?php echo $width; ?>),
			        height: Math.round(scaleY * <?php echo $height; ?>),
			        marginLeft: -Math.round(scaleX * c.x1),
			        marginTop: -Math.round(scaleY * c.y1)
			    });

			}
		});
	});
</script>
		<?php
}

function gd_useravatar_delete_filename($uid) {

	// Only if the flag to save the filename is on
	if (!USERAVATARPOP_USEMETA) {
		return;
	}

	delete_user_meta($uid, USERAVATARPOP_FILENAME);
}

function gd_useravatar_save_filename($uid, $filename) {

	// Only if the flag to save the filename is on
	if (!USERAVATARPOP_USEMETA) {
		return;
	}

	// Delete the current filename
	gd_useravatar_delete_filename($uid);

	// Save the user meta with the avatar filename
	add_user_meta($uid, USERAVATARPOP_FILENAME, $filename, true);
}

function gd_useravatar_get_filename($uid) {

	// Only if the flag to save the filename is on
	if (!USERAVATARPOP_USEMETA) {
		return null;
	}

	// Save the user meta with the avatar filename
	return get_user_meta($uid, USERAVATARPOP_FILENAME, true);
}

/**
 * user_avatar_add_photo_step3 function.
 * The Third Step in the Process
 * Description: Deletes previous uploaded picture and creates a new cropped image in its place.
 * @access public
 * @param mixed $uid
 * @return void
 */
function user_avatar_add_photo_step3($uid) {

	if ( $_POST['oitar'] > 1 ) {
		$_POST['x1'] = $_POST['x1'] * $_POST['oitar'];
		$_POST['y1'] = $_POST['y1'] * $_POST['oitar'];
		$_POST['width'] = $_POST['width'] * $_POST['oitar'];
		$_POST['height'] = $_POST['height'] * $_POST['oitar'];
	}

	$original_file = get_transient( 'avatar_file_'.$uid );
					 delete_transient('avatar_file_'.$uid );

	// Hack PoP Plug-in: Allow PoP AWS to download the file from the bucket to the local disk
	do_action('user_avatar_add_photo:retrieve-file', $original_file, $uid);

	if( !file_exists($original_file) ) {
		echo "<div class='error'><p>". __('Sorry, No file available','user-avatar')."</p></div>";
		return true;
	}

	// Hack PoP Plug-in: externalize content into a separate function
	gd_user_avatar_add_photo($uid, $original_file, $_POST);

	/* Remove the original */
	@unlink( $original_file );

	if ( is_wp_error( $thumb ) )
		wp_die( __( 'Image could not be processed.  Please go back and try again.' ), __( 'Image Processing Error' ) );
	?>
	<script type="text/javascript">
		self.parent.user_avatar_refresh_image('<?php echo user_avatar_get_avatar($uid, 150); ?>');
		self.parent.add_remove_avatar_link();
	</script>
	<div id="user-avatar-step3">
		<h3><?php _e("Here's your new profile picture...",'user-avatar'); ?></h3>
		<span style="float:left;">
		<?php
		echo user_avatar_get_avatar( $uid, 150);
		?>
		</span>
		<a id="user-avatar-step3-close" class="button" onclick="self.parent.tb_remove();" ><?php _e('Close','user-avatar'); ?></a>
	</div>
<?php
}

// Hack PoP Plug-in: remove spaces, trim, etc
// Function copied from BlueImp uploadHandler.php: protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range)
function gd_useravatar_trim_file_name($file_path, $name) {

    // Remove path information and dots around the filename, to prevent uploading
    // into different directories or replacing hidden system files.
    // Also remove control characters and spaces (\x00..\x20) around the filename:
    $name = trim(basename(stripslashes($name)), ".\x00..\x20");

    // Hack PoP Plug-in: it is not really replacing spaces in the name, so do it now
    $name = str_replace(' ', '-', $name);

    // Use a timestamp for empty filenames:
    if (!$name) {
        $name = str_replace('.', '-', microtime(true));
    }
    // // Add missing file extension for known image types:
    // if (strpos($name, '.') === false &&
    //         preg_match('/^image\/(gif|jpe?g|png)/', $type, $matches)) {
    //     $name .= '.'.$matches[1];
    // }
    if (function_exists('exif_imagetype')) {
        switch(exif_imagetype($file_path)){
            case IMAGETYPE_JPEG:
            	$type = 'jpg';
                $extensions = array('jpg', 'jpeg');
                break;
            case IMAGETYPE_PNG:
            	$type = 'png';
                $extensions = array('png');
                break;
            case IMAGETYPE_GIF:
            	$type = 'gif';
                $extensions = array('gif');
                break;
        }
        // Add missing file extension for known image types:
	    if (strpos($name, '.') === false) {
	        $name .= '.'.$type;
	    }
        // Adjust incorrect image file extensions:
        if (!empty($extensions)) {
            $parts = explode('.', $name);
            $extIndex = count($parts) - 1;
            // Hack PoP Plug-in: if the filename ext is in uppercase (eg: JPG) transform to lowercase, because somehow uploading to AWS S3 will transform it to lowercase, so standardize
            // $ext = strtolower(@$parts[$extIndex]);
            $ext = @$parts[$extIndex];
            if (!in_array($ext, $extensions)) {
                $parts[$extIndex] = $extensions[0];
                $name = implode('.', $parts);
            }
        }
    }
    return $name;
}

// Hack PoP Plug-in: externalize content into a separate function
function gd_user_avatar_add_photo($uid, $original_file, $cropinfo = array()) {

	// Hack PoP Plug-in: variable $filepath changed, variable $folderpath and $filename are new
	// Comment Leo 05/02/2016: to add compatibility to the structure for both AWS and non-AWS versions,
	// keep the name of the file as the original, and regenerate the structure from the folders, with each folder containing 1 file

	$filename = basename($original_file);//"{$uid}_".time();

	// Hack PoP Plug-in: remove spaces, trim, etc
	// Function copied from BlueImp uploadHandler.php
	$filename = gd_useravatar_trim_file_name($original_file, $filename);

	$folderpath = USER_AVATAR_UPLOAD_PATH."{$uid}/";
	$originalpath = $folderpath.'original/';
	$original = $originalpath.$filename;
	$thumbpath = $folderpath.'thumb/';
	$thumb = $thumbpath.$filename;
	$photopath = $folderpath.'photo/';
	$photo = $photopath.$filename;

	// delete the previous files
	user_avatar_delete_files($uid);

	// if(!file_exists($folderpath)) {

	// Create the structure again
	mkdir($folderpath);

	// Hack PoP Plug-in: create extra folder also
	mkdir($originalpath);
	mkdir($thumbpath);
	mkdir($photopath);
	// }

	if (!$cropinfo) {

		// Center squared crop area
		$file_size = getimagesize($original_file);
		$file_size_min = ($file_size[0] < $file_size[1] ? $file_size[0] : $file_size[1]);
		$cropinfo['x1'] = ($file_size[0] - $file_size_min) / 2;
		$cropinfo['y1'] = ($file_size[1] - $file_size_min) / 2;
		$cropinfo['width'] = $cropinfo['height'] = $file_size_min;
	}

	// Hack PoP Plug-in: copy the original, resize the original, crop the thumb
	copy($original_file, $original);
	$image = wp_get_image_editor( $original_file );
	if ( ! is_wp_error( $image ) ) {
	    $image->resize( USER_AVATAR_PHOTO_MAXWIDTH, USER_AVATAR_PHOTO_MAXHEIGHT );
	    $image->save( $photo );
	}
	$thumb = wp_crop_image( $original_file, $cropinfo['x1'], $cropinfo['y1'], $cropinfo['width'], $cropinfo['height'], USER_AVATAR_FULL_WIDTH, USER_AVATAR_FULL_HEIGHT, false, $thumb );

	// Hack PoP Plug-in: create extra avatars
	gd_useravatar_save_avatars($original_file, $filename, $folderpath, $thumb);

	// Save the user meta with the avatar filename
	gd_useravatar_save_filename($uid, $filename);

	// Hack PoP Plug-in: allow for further actions, eg: uploading the image to S3
	do_action('gd_user_avatar', $uid, $folderpath, $filename, $original, $thumb, $photo);
}
/**
 * user_avatar_delete_files function.
 * Description: Deletes the avatar files based on the user id.
 * @access public
 * @param mixed $uid
 * @return void
 */
// Hack PoP Plug-in: directly delete the folder using the recursive function below
// recursive function taken from https://secure.php.net/manual/en/function.rmdir.php
// function user_avatar_delete_files($uid)
// {
// 	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$uid}/";
// 	if ( !file_exists( $avatar_folder_dir ) )
// 		return false;

// 	if ( is_dir( $avatar_folder_dir ) && $av_dir = opendir( $avatar_folder_dir ) ) {
// 		while ( false !== ( $avatar_file = readdir($av_dir) ) ) {
// 				@unlink( $avatar_folder_dir . '/' . $avatar_file );
// 		}
// 		closedir($av_dir);
// 	}

// 	@rmdir( $avatar_folder_dir );
// }
function user_avatar_delete_files($uid)	{

	$dir = USER_AVATAR_UPLOAD_PATH."{$uid}/";
	return delTree($dir);
}

/**
 * Based on the
 * user_avatar_core_fetch_avatar_filter() 1.2.5 BP
 *
 * Description: Attempts to filter get_avatar function and let Word/BuddyPress have a go at
 * 				finding an avatar that may have been uploaded locally.
 *
 * @param string $avatar The result of get_avatar from before-filter
 * @param int|string|object $user A user ID, email address, or comment object
 * @param int $size Size of the avatar image (thumb/full)
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternate text to use in image tag. Defaults to blank
 * @return <type>
 */
function user_avatar_fetch_avatar_filter( $avatar, $user, $size, $default, $alt ) {
	global $pagenow;

	//If user is on discussion page, return $avatar
    if($pagenow == "options-discussion.php")
    	return $avatar;

	// If passed an object, assume $user->user_id
	if ( is_object( $user ) )
		$id = $user->user_id;

	// If passed a number, assume it was a $user_id
	else if ( is_numeric( $user ) )
		$id = $user;

	// If passed a string and that string returns a user, get the $id
	else if ( is_string( $user ) && ( $user_by_email = get_user_by_email( $user ) ) )
		$id = $user_by_email->ID;

	// If somehow $id hasn't been assigned, return the result of get_avatar
	if ( empty( $id ) )
		return !empty( $avatar ) ? $avatar : $default;

	// check yo see if there is a file that was uploaded by the user
	if( user_avatar_avatar_exists($id) ):

		$user_avatar = user_avatar_fetch_avatar( array( 'item_id' => $id, 'width' => $size, 'height' => $size, 'alt' => $alt ) );
		if($user_avatar)
			return $user_avatar;
	// Hack PoP Plug-in: all below commented, only 1 line at the end including a filter to change the result
	// 	else
	// 		return !empty( $avatar ) ? $avatar : $default;
	// else:
	// 	return !empty( $avatar ) ? $avatar : $default;
	endif;
	// for good measure
	// Hack PoP Plug-in: add filter to change default result
	return apply_filters('gd_avatar_default', (!empty( $avatar ) ? $avatar : $default), $user, $size, $default, $alt);
}

add_filter( 'get_avatar', 'user_avatar_fetch_avatar_filter', 10, 5 );

/**
 * user_avatar_core_fetch_avatar()
 *
 * Description: Fetches an avatar from a BuddyPress object. Supports user/group/blog as
 * 				default, but can be extended to include your own custom components too.
 *
 * @global object $bp
 * @global object $current_blog
 * @param array $args Determine the output of this function
 * @return string Formatted HTML <img> element, or raw avatar URL based on $html arg
 */
function user_avatar_fetch_avatar( $args = '' ) {

	$defaults = array(
		'item_id'		=> false,
		'object'		=> "user",		// user/group/blog/custom type (if you use filters)
		'type'			=> 'full',		// thumb or full
		'avatar_dir'	=> false,		// Specify a custom avatar directory for your object
		// Hack PoP Plug-in: changed from false to 150
		'width'			=> 150,		// Custom width (int)
		'height'		=> 150,		// Custom height (int)
		'class'			=> '',			// Custom <img> class (string)
		'css_id'		=> false,		// Custom <img> ID (string)
		'alt'			=> '',	// Custom <img> alt (string)
		'email'			=> false,		// Pass the user email (for gravatar) to prevent querying the DB for it
		'no_grav'		=> false,		// If there is no avatar found, return false instead of a grav?
		'html'			=> true			// Wrap the return img URL in <img />
	);

	// Compare defaults to passed and extract
	$params = wp_parse_args( $args, $defaults );
	extract( $params, EXTR_SKIP );

	// Hack PoP Plug-in: comment unneeded code
	// if($width > 50)
	// 	$type = "full";

	// Hack PoP Plug-in: comment unneeded code
	// $avatar_size = ( 'full' == $type ) ? '-bpfull' : '-bpthumb';
	$class .= " avatar ";
	$class .= " avatar-". $width ." ";
	$class .= " photo";

	if ( false === $alt)
		$safe_alt = '';
	else
		$safe_alt = esc_attr( $alt );


	// Add an identifying class to each item
	$class .= ' ' . $object . '-' . $item_id . '-avatar';

	// Set CSS ID if passed
	if ( !empty( $css_id ) )
		$css_id = " id=\"".esc_attr($css_id)."\"";

	// Hack PoP Plug-in: comment unneeded code
	$html_width = " width=\"".esc_attr($width)."\"";
	// // Set avatar width
	// if ( $width )
	// 	$html_width = " width=\"".esc_attr($width)."\"";
	// else
	// 	$html_width = ( 'thumb' == $type ) ? ' width="' . esc_attr(USER_AVATAR_THUMB_WIDTH) . '"' : ' width="' . esc_attr(USER_AVATAR_FULL_WIDTH) . '"';

	// Hack PoP Plug-in: comment unneeded code
	$html_height = " height=\"".esc_attr($height)."\"";
	// Set avatar height
	// if ( $height )
	// 	$html_height = " height=\"".esc_attr($height)."\"";
	// else
	// 	$html_height = ( 'thumb' == $type ) ? ' height="' . esc_attr(USER_AVATAR_THUMB_HEIGHT) . '"' : ' height="' . esc_attr(USER_AVATAR_FULL_HEIGHT) . '"';


	// Hack PoP Plug-in: all of it pretty much
	if ($avatar_data = user_avatar_avatar_exists($item_id, array('size' => $width))):

		$avatar_url = $avatar_data['url'];

		// Hack PoP Plug-in: comment all code below, unneeded
		// // Hack PoP Plug-in: this is a bug, it must use USER_AVATAR_URL where the path can be overriden
		// // Also already point to folder "/thumb"
		// //$avatar_src = get_site_url()."/wp-content/uploads/avatars/".$item_id."/".$avatar_img;
		// $avatar_src = USER_AVATAR_URL.$item_id."/thumb/".$avatar_img;
		// if(function_exists('is_subdomain_install') && !is_subdomain_install())
		// 	// Hack PoP Plug-in: same problems with this line here
		// 	//$avatar_src = "/wp-content/uploads/avatars/".$item_id."/".$avatar_img;
		// 	$avatar_src = "/".USER_AVATAR_UPLOAD_PATH.$item_id."/thumb/".$avatar_img;

		// // Hack PoP Plug-in: add /thumb
		// $avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$item_id}/thumb/";
		// $file_time = filemtime ($avatar_folder_dir."/".$avatar_img);

		// $avatar_url = plugins_url('/user-avatar/user-avatar-pic.php')."?src=".$avatar_src ."&w=".$width."&id=".$item_id."&random=".$file_time;

		// Hack PoP Plug-in: add a filter for the response, so we can add a hook to replace the string
		// So all the code below has been modified
		$avatar = '';

		// Return it wrapped in an <img> element
		if ( true === $html ) { // this helps validate stuff
			$avatar = '<img src="' . esc_url($avatar_url) . '" alt="' . esc_attr($alt) . '" class="' . esc_attr($class) . '"' . $css_id . $html_width . $html_height . ' />';
		// ...or only the URL
		} else {
			$avatar = $avatar_url ;
		}

		return apply_filters('gd_user_avatar_fetch_avatar', $avatar, $user_id, $width, $height);
	else:
		return false;
	endif;
}

// Hack PoP Plug-in
function gd_useravatar_avatar_sizes() {

	return apply_filters('gd_useravatar_avatar_sizes', array());
}

// Hack PoP Plug-in
function gd_useravatar_save_avatars($original_file, $filename, $dest_filepath, $cropped_file = null/*, $check_exists = false*/) {

	// Crop them to make them square
	/** WordPress Image Administration API */
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// /** WordPress Media Administration API */
	require_once(ABSPATH . 'wp-admin/includes/media.php');

	// Generate further avatars
	if ($thumbsizes = gd_useravatar_avatar_sizes()) {

		$dest_filepath = $dest_filepath.'avatars/';
		mkdir($dest_filepath);
		// if(!file_exists($dest_filepath)) {

		// 	mkdir($dest_filepath);
		// }

		// Use either the original file or the cropped one, depending on if this one exists (it is provided by User Avatar)
		$src_file = $cropped_file ? $cropped_file : $original_file;

		// Center squared crop area
		$file_size = getimagesize($src_file);
		$file_size_min = ($file_size[0] < $file_size[1] ? $file_size[0] : $file_size[1]);
		$file_x1 = ($file_size[0] - $file_size_min) / 2;
		$file_y1 = ($file_size[1] - $file_size_min) / 2;

		foreach ($thumbsizes as $thumbsize) {

			// $cropped_thumbsize = $dest_filepath.'-'.$thumbsize.".jpg";
			$avatarpath = $dest_filepath.$thumbsize.'/';
			if(!file_exists($avatarpath)) {

				mkdir($avatarpath);
			}
			$cropped_thumbsize = $avatarpath.$filename;

			// if ($check_exists) {

			// 	if(file_exists($cropped_thumbsize)) continue;
			// }

			// Calculate coordinates to crop
			$cropped_thumbsize = wp_crop_image( $src_file, $file_x1, $file_y1, $file_size_min, $file_size_min, $thumbsize, $thumbsize, false, $cropped_thumbsize );
		}
	}
}


add_action("admin_init", "user_avatar_delete");
/**
 * user_avatar_delete function.
 *
 * @access public
 * @return void
 */
function user_avatar_delete(){

		global $pagenow;

		$current_user = wp_get_current_user();

		// If user clicks the remove avatar button, in URL deleter_avatar=true
		if( isset($_GET['delete_avatar']) && wp_verify_nonce($_GET['_nononce'] ?? null, 'user_avatar') && ( ($_GET['u'] ?? null) == $current_user->id || current_user_can('edit_users')) )
		{
			$user_id = $_GET['user_id'] ?? null;
			if(is_numeric($user_id))
				$user_id = "?user_id=".$user_id;

			user_avatar_delete_files($_GET['u'] ?? null);
			wp_redirect(get_option('siteurl') . '/wp-admin/'.$pagenow.$user_id);

		}
}
/**
 * user_avatar_form function.
 * Description: Creation and calling of appropriate functions on the overlay form.
 * @access public
 * @param mixed $profile
 * @return void
 */
function user_avatar_form($profile)
{
	global $current_user;

	// Check if it is current user or super admin role
	if( $profile->ID == $current_user->ID || current_user_can('edit_user', $current_user->ID) || is_super_admin($current_user->ID) )
	{
		$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$profile->ID}/";
	?>
	<div id="user-avatar-display" class="submitbox" >
	<h3 ><?php _e('Picture','user-avatar'); ?></h3>
	<p id="user-avatar-display-image"><?php echo user_avatar_get_avatar($profile->ID, 150); ?></p>
	<a id="user-avatar-link" class="button-primary thickbox" href="<?php echo admin_url('admin-ajax.php'); ?>?action=user_avatar_add_photo&step=1&uid=<?php echo $profile->ID; ?>&TB_iframe=true&width=720&height=450" title="<?php _e('Upload and Crop an Image to be Displayed','user-avatar'); ?>" ><?php _e('Update Picture','user-avatar'); ?></a>

	<?php
		// Remove the User-Avatar button if there is no uploaded image

		if(isset($_GET['user_id'])):
			$remove_url = admin_url('user-edit.php')."?user_id=".$_GET['user_id']."&delete_avatar=true&_nononce=". wp_create_nonce('user_avatar')."&u=".$profile->ID;
		else:
			$remove_url = admin_url('profile.php')."?delete_avatar=true&_nononce=". wp_create_nonce('user_avatar')."&u=".$profile->ID;

		endif;
		if ( user_avatar_avatar_exists($profile->ID) ):?>
			<a id="user-avatar-remove" class="submitdelete deleteaction" href="<?php echo esc_url_raw($remove_url); ?>" title="<?php _e('Remove User Avatar Image','user-avatar'); ?>" ><?php _e('Remove','user-avatar'); ?></a>
			<?php
		endif;
	?>
	</div>
	<script type="text/javascript">
	function user_avatar_refresh_image(img){
	 jQuery('#user-avatar-display-image').html(img);
	}
	function add_remove_avatar_link(){
		if(!jQuery("#user-avatar-remove").is('a')){
			jQuery('#user-avatar-link').after(" <a href='<?php echo $remove_url; ?>' class='submitdelete'  id='user-avatar-remove' ><?php _e('Remove','user-avatar'); ?></a>")
		}


	}

	</script>
	<?php
	}
}

/*-- HELPER FUNCTIONS --*/
/**
 * user_avatar_avatar_exists function.
 *
 * @access public
 * @param mixed $id
 * @return void
 */
// Hack PoP Plug-in: add the $size
function user_avatar_avatar_exists($id, $args = ''){

	$defaults = array(
		'size'				=> false,
		'source'			=> 'thumb',			// $source: original / thumb / photo
	);

	// Compare defaults to passed and extract
	$params = wp_parse_args( $args, $defaults );

	// Hack PoP Plug-in: If the function has a filter, then execute this one instead
	// This way we allow the AWS logic to take over
	if (has_filter('user-avatar:exists')) {
		return apply_filters('user-avatar:exists', false, $id, $params);
	}

	extract( $params, EXTR_SKIP );

	// If using meta, then simply check if the meta exists
	if (USERAVATARPOP_USEMETA) {

		if ($filename = gd_useravatar_get_filename($id)) {

			// If the size is among the list of avatars generated, then return that one
			if ($size && in_array($size, gd_useravatar_avatar_sizes())) {

				return array(
					'url' => USER_AVATAR_URL."{$id}/avatars/{$size}/".$filename,
					'file' => USER_AVATAR_UPLOAD_PATH."{$id}/avatars/{$size}/".$filename,
				);
			}

			// Otherwise, return the generic one
			return array(
				'url' => USER_AVATAR_URL."{$id}/{$source}/".$filename,
				'file' => USER_AVATAR_UPLOAD_PATH."{$id}/{$source}/".$filename,
			);
		}

		return false;
	}

	// If not using meta, check if the file actually exists in the folder

	// First check if the $size is given, if so try to find the avatar with that size
	if ($size) {

		// The file is unique in this folder
		$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$id}/avatars/{$size}/";
		if (file_exists($avatar_folder_dir) && is_dir($avatar_folder_dir)) {

			$allfiles = scandir($avatar_folder_dir);

			// Skip . and .. folders
			array_shift($allfiles);
			array_shift($allfiles);
			if ($filename = $allfiles[0]) {

				$filename = basename($filename);
				return array(
					'url' => USER_AVATAR_URL.$id."/avatars/{$size}/".$filename,
					'file' => $avatar_folder_dir.$filename,
				);
			}
		}
	}

	// The file is unique in this folder
	$avatar_folder_dir = USER_AVATAR_UPLOAD_PATH."{$id}/{$source}/";
	if (file_exists($avatar_folder_dir) && is_dir($avatar_folder_dir)) {

		$allfiles = scandir($avatar_folder_dir);

		// Skip . and .. folders
		array_shift($allfiles);
		array_shift($allfiles);
		if ($filename = $allfiles[0]) {

			$filename = basename($filename);
			return array(
				'url' => USER_AVATAR_URL.$id."/{$source}/".$filename,
				'file' => $avatar_folder_dir.$filename,
			);
		}
	}

	return false;
}

/**
 * user_avatar_get_avatar function.
 *
 * @access public
 * @param mixed $id
 * @param mixed $width
 * @return void
 */
function user_avatar_get_avatar($id,$width) {

	if(! get_option('show_avatars')):

		if( user_avatar_avatar_exists($id) ):

			$user_avatar = user_avatar_fetch_avatar( array( 'item_id' => $id, 'width' => $width, 'height' => $width, 'alt' => '' ) );
			if($user_avatar):
				return $user_avatar;
			else:
				return '<img src="'.USER_AVATAR_DEFAULT.'" width="'.$width.'" height="'.$width.'" class="avatar" />';
			endif;
		else:
			return '<img src="'.USER_AVATAR_DEFAULT.'" width="'.$width.'" height="'.$width.'" class="avatar" />';
		endif;
	else:
		return get_avatar($id,$width);
	endif;
}
/* --- END OF FILE --- */

