<?php $args = !empty($args) ? $args:array(); /* @var $args array */ ?>
<!-- START Geo Units Search -->
<div class="em-search-geo-units em-search-field" <?php if( empty($args['geo']) || empty($args['near']) ): ?>style="display:none;"<?php endif; /* show location fields if no geo search is made */ ?>>
	<label>
		<span><?php echo esc_html($args['geo_units_label']); ?></span>
		<select name="near_distance" class="em-search-geo-distance">
		    <?php foreach( $args['geo_distance_values'] as $unit ) : ?>
			<option value="<?php echo $unit; ?>" <?php if($args['near_distance'] == $unit) echo 'selected="selected"' ?>><?php echo $unit; ?></option>
			<?php endforeach; ?>
		</select>
	</label>
	<label>
		<span class="screen-reader-text"><?php echo esc_html(__('distance units','events-manager')); ?></span>
		<select name="near_unit" class="em-search-geo-unit">
			<option value="mi">mi</option>
			<option value="km" <?php if($args['near_unit'] == 'km') echo 'selected="selected"' ?>>km</option>
		</select>
	</label>
</div>
<!-- END Geo Units Search -->