	<?php $pagesection_templates = array(
		GD_TEMPLATE_PAGESECTION_HOME, 
		GD_TEMPLATE_PAGESECTION_TAG, 
		GD_TEMPLATE_PAGESECTION_PAGE, 
		GD_TEMPLATE_PAGESECTION_SINGLE, 
		GD_TEMPLATE_PAGESECTION_AUTHOR, 
		GD_TEMPLATE_PAGESECTION_404
	); ?>
	<?php 
	$scrollable_classes = '';
	if (PoPTheme_Wassup_Utils::add_mainpagesection_scrollbar()) {
		$scrollable_classes = 'pop-waypoints-context scrollable perfect-scrollbar vertical';
	}
	?>
	<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_MAIN ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_MAIN ?>-merge" data-offcanvas="main" class="offcanvas main <?php echo $scrollable_classes ?> pop-merge <?php echo implode(' ', $pagesection_templates); ?>">
		<div id="main" class="container-fluid perfect-scrollbar-offsetreference">
			<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_MAIN ?>-merge"></div>
		</div>
	</div>