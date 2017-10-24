<?php $sideinfo_templates = array(
	GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME,
); ?>
<?php 
	$openmode = apply_filters('templates:sideinfo:openmode', 'automatic');
?>
<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-pagesection-openmode="<?php echo $openmode ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>-merge" data-offcanvas="sideinfo" class="offcanvas pop-waypoints-context scrollable sideinfo perfect-scrollbar vertical pop-merge <?php echo implode(' ', $sideinfo_templates); ?>">
	<div class="container-fluid perfect-scrollbar-offsetreference">
		<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_SIDEINFO ?>-merge"><?php echo PoP_ServerSideRendering_Utils::render_pagesection(GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO) ?></div>
	</div>
</div>