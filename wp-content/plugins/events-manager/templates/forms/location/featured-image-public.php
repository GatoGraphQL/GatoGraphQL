<?php
/*
 * This file is called by templates/forms/location-editor.php to display fields for uploading images on your location form on your website. This does not affect the admin featured image section.
* You can override this file by copying it to /wp-content/themes/yourtheme/plugins/events-manager/forms/location/ and editing it there.
*/
global $EM_Location;
/* @var $EM_Location EM_Location */
?>
<p id="location-image-img">
<?php if ($EM_Location->get_image_url() != '') : ?>
	<img src='<?php echo $EM_Location->get_image_url('medium'); ?>' alt='<?php echo $EM_Location->location_name ?>'/>
<?php else : ?> 
	<?php _e('No image uploaded for this location yet', 'events-manager') ?>
<?php endif; ?>
</p>
<label for='location_image'><?php _e('Upload/change picture', 'events-manager') ?></label> <input id='location-image' name='location_image' id='location_image' type='file' size='40' />
<br />
<?php if ($EM_Location->get_image_url() != '') : ?>
<label for='location_image_delete'><?php _e('Delete Image?', 'events-manager') ?></label> <input id='location-image-delete' name='location_image_delete' id='location_image_delete' type='checkbox' value='1' />
<?php endif; ?>