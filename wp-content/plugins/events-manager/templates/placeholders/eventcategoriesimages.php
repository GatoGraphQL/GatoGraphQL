<?php
/* @var $EM_Event EM_Event */
$count_cats = count($EM_Event->get_categories()->categories) > 0;
if( $count_cats > 0 ){
	$one_image = false;
	?>
	<ul class="event-categories-images">
		<?php foreach($EM_Event->get_categories() as $EM_Category): /* @var $EM_Category EM_Category */ ?>
			<?php if( $EM_Category->get_image_url() != '' ): ?>
			<li><?php echo $EM_Category->output('<a href="#_CATEGORYURL" title="#_CATEGORYNAME">#_CATEGORYIMAGE</a>'); $one_image = true; ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if (!$one_image): ?>
			<li><?php echo get_option ( 'dbem_no_categories_message' ); ?></li>
		<?php endif; ?>
	</ul>
	<?php
}else{
	echo get_option ( 'dbem_no_categories_message' );
}