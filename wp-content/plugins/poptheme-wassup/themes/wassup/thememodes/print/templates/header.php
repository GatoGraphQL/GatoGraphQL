<div id="<?php echo GD_TEMPLATEID_PAGESECTIONGROUP_ID ?>" class="pop-pagesection-group pagesection-group">
	<?php include POPTHEME_WASSUP_TEMPLATES.'/status.php' ?>
	<?php $sideinfo_templates = array(
		GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY,
		GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR,
		GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE,
		GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG,
		GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE,
		GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME,
	); ?>
	<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>-merge" data-offcanvas="sideinfo" class="sideinfo pop-merge <?php echo implode(' ', $sideinfo_templates); ?>">
		<div class="container-fluid perfect-scrollbar-offsetreference">
			<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>-merge"></div>
		</div>
	</div>