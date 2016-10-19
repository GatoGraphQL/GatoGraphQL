<?php $coauthors = get_coauthors( $this->get( 'post_id' ) ); ?>
<li class="amp-wp-byline">
	<?php coauthors(); ?>
</li>
