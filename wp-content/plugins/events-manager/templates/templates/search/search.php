<?php
/* This general search will find matches within event_name, event_notes, and the location_name, address, town, state and country. */ 
$args = !empty($args) ? $args:array(); /* @var $args array */ 
?>
<!-- START General Search -->
<div class="em-search-text em-search-field">
	<script type="text/javascript">
	EM.search_term_placeholder = '<?php echo esc_attr($args['search_term_label']); ?>';
	</script>
	<input type="text" name="em_search" class="em-events-search-text em-search-text" value="<?php echo esc_attr($args['search']); ?>" />
</div>
<!-- END General Search -->