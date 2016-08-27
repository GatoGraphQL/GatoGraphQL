<?php
/* @var $EM_Event EM_Event */
$count_cats = count($EM_Event->get_categories()->categories) > 0;
if( $count_cats > 0 ){
	?>
	<ul class="event-categories">
		<?php foreach($EM_Event->get_categories() as $EM_Category): ?>
			<li><?php echo $EM_Category->output("#_CATEGORYLINK"); ?></li>
		<?php endforeach; ?>
	</ul>
	<?php	
}else{
	echo get_option ( 'dbem_no_categories_message' );
}