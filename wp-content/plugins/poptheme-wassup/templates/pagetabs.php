<?php $pagetabs_templates = array(
	GD_TEMPLATE_PAGESECTION_PAGETABS_HOME,
	GD_TEMPLATE_PAGESECTION_PAGETABS_TAG,
	GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE,
	GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE,
	GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR,
	GD_TEMPLATE_PAGESECTION_PAGETABS_404,
); ?>
<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_PAGETABS ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-pagesection-openmode="manual" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_PAGETABS ?>-merge" data-offcanvas="pagetabs" class="offcanvas pop-waypoints-context scrollable pagetabs perfect-scrollbar horizontal pop-merge <?php echo implode(' ', $pagetabs_templates); ?> navbar navbar-main navbar-inverse">
	<div class="perfect-scrollbar-offsetreference">
		<div class="pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_PAGETABS ?>-merge"><?php echo PoP_ServerSideRendering_Factory::get_instance()->render_pagesection(GD_TEMPLATEID_PAGESECTIONSETTINGSID_PAGETABS) ?></div>
	</div>
</div>
