<?php
/*
 * This file is called by templates/forms/location-editor.php to display attribute fields on your location form on your website.
 * You can override this file by copying it to /wp-content/themes/yourtheme/plugins/events-manager/forms/location/ and editing it there.
 */
global $EM_Location;
/* @var $EM_Location EM_Location */
$attributes = em_get_attributes(true); //lattributes only
$has_deprecated = false;
?>
<?php if( count( $attributes['names'] ) > 0 ) : ?>
	<?php foreach( $attributes['names'] as $name) : ?>
	<div class="location-attributes">
		<label for="em_attributes[<?php echo $name ?>]"><?php echo $name ?></label>
		<?php if( count($attributes['values'][$name]) > 1 ): ?>
		<select name="em_attributes[<?php echo $name ?>]">
			<?php foreach($attributes['values'][$name] as $attribute_val): ?>
				<?php if( is_array($EM_Location->location_attributes) && array_key_exists($name, $EM_Location->location_attributes) && $EM_Location->location_attributes[$name]==$attribute_val ): ?>
					<option selected="selected"><?php echo $attribute_val; ?></option>
				<?php else: ?>
					<option><?php echo $attribute_val; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php else: ?>
		<input type="text" name="em_attributes[<?php echo $name ?>]" value="<?php echo array_key_exists($name, $EM_Location->location_attributes) ? esc_attr($EM_Location->location_attributes[$name], ENT_QUOTES):''; ?>" />
		<?php endif; ?>
	</div>
	<?php endforeach; ?>
<?php endif; ?>