<?php
global $EM_Location;
$attributes = em_get_attributes(true); //get Lattributes
$has_deprecated = false;
?>
<div id="location-attributes">
	<?php if( !empty($attributes['names']) && count( $attributes['names'] ) > 0 ) : ?>
		<table class="form-table">
			<thead>
				<tr valign="top">
					<td><strong>Attribute Name</strong></td>
					<td><strong>Value</strong></td>
				</tr>
			</thead> 
			<tbody id="mtm_body">
				<?php
				$count = 1;
				foreach( $attributes['names'] as $name){
					?>
					<tr valign="top" id="em_attribute_<?php echo $count ?>">
						<td scope="row"><?php echo $name ?></td>
						<td>
							<?php if( count($attributes['values'][$name]) > 1 ): ?>
							<select name="em_attributes[<?php echo $name ?>]">
								<?php foreach($attributes['values'][$name] as $attribute_val): ?>
									<?php if( array_key_exists($name, $EM_Location->location_attributes) && $EM_Location->location_attributes[$name]==$attribute_val ): ?>
										<option selected="selected"><?php echo $attribute_val; ?></option>
									<?php else: ?>
										<option><?php echo $attribute_val; ?></option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
							<?php else: ?>
							<input type="text" name="em_attributes[<?php echo $name ?>]" value="<?php echo array_key_exists($name, $EM_Location->location_attributes) ? esc_attr($EM_Location->location_attributes[$name], ENT_QUOTES):''; ?>" />
							<?php endif; ?>
						</td>
					</tr>
					<?php
					$count++;
				}
				if($count == 1){
					?>
					<tr><td colspan="2"><?php echo sprintf(__("You don't have any custom attributes defined in any of your Locations Manager template settings. Please add them the <a href='%s'>settings page</a>",'events-manager'),EM_ADMIN_URL ."&amp;page=locations-manager-options"); ?></td></tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php if( count(array_diff(array_keys($EM_Location->location_attributes), $attributes['names'])) > 0 ): ?>
		<p><strong><?php _e('Deprecated Attributes', 'events-manager')?></strong></p>
		<p><em><?php _e("If you see any attributes under here, that means they're not used in Locations Manager formats. To add them, you need to add the custom attribute again to a formatting option in the settings page. To remove any of these deprecated attributes, give it a blank value and save.",'events-manager') ?></em></p>
		<table class="form-table">
			<thead>
				<tr valign="top">
					<td><strong>Attribute Name</strong></td>
					<td><strong>Value</strong></td>
				</tr>
			</thead> 
			<tbody id="mtm_body">
				<?php
				if( is_array($EM_Location->location_attributes) and count($EM_Location->location_attributes) > 0){
					foreach( $EM_Location->location_attributes as $name => $value){
						if( is_array($value) ) $value = serialize($value);
						if( !in_array($name, $attributes['names']) ){
							?>
							<tr valign="top" id="em_attribute_<?php echo $count ?>">
								<td scope="row"><?php echo $name ?></td>
								<td>
									<input type="text" name="em_attributes[<?php echo $name ?>]" value="<?php echo esc_attr($value, ENT_QUOTES); ?>" />
								</td>
							</tr>
							<?php
							$count++;
						}
					}
				}
				?>
			</tbody>
		</table>
		<?php endif; ?>
	<?php else : ?>
		<p>
		<?php _e('In order to use attributes, you must define some in your templates, otherwise they\'ll never show. Go to Events > Settings > General to add attribute placeholders.', 'events-manager'); ?>
		</p> 
		<script>
			jQuery(document).ready(function($){ $('#location_attributes').addClass('closed'); });
		</script>
	<?php endif; ?>
</div>