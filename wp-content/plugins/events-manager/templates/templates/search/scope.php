<?php $args = !empty($args) ? $args:array(); /* @var $args array */ ?>
<!-- START Date Search -->
<div class="em-search-scope em-search-field">
	<span class="em-search-scope em-events-search-dates em-date-range">
		<label>
			<span><?php echo esc_html($args['scope_label']); ?></span>
			<input type="text" class="em-date-input-loc em-date-start" />
			<input type="hidden" class="em-date-input" name="scope[0]" value="<?php echo esc_attr($args['scope'][0]); ?>" />
		</label>
		<label>
			<?php echo esc_html($args['scope_seperator']); ?>
			<input type="text" class="em-date-input-loc em-date-end" />
			<input type="hidden" class="em-date-input" name="scope[1]" value="<?php echo esc_attr($args['scope'][1]); ?>" />
		</label>
	</span>
</div>
<!-- END Date Search -->