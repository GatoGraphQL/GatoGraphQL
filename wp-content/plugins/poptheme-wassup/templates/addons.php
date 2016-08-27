<?php $addons_window_id = GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_ADDONS, 'window') ?>
<?php $addons_templates = array(
	GD_TEMPLATE_PAGESECTION_ADDONS_HOME,
	GD_TEMPLATE_PAGESECTION_ADDONS_TAG,
	GD_TEMPLATE_PAGESECTION_ADDONS_PAGE,
	GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE,
	GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR,
	GD_TEMPLATE_PAGESECTION_ADDONS_404,
); ?>
<?php $addontabs_templates = array(
	GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME,
	GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG,
	GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE,
	GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE,
	GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR,
	GD_TEMPLATE_PAGESECTION_ADDONTABS_404,
); ?>
<?php /* Default state for the Window: maximized */ ?>
<div id="<?php echo $addons_window_id ?>" class="window maximized">
	<?php /* IMPORTANT: Place ADDONS before ADDONTABS so that ADDONS interceptors execute first. This is needed since these must first execute `replicateTopLevel` */ ?>
	<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONS ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_ADDONS ?>" data-clickframetarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONS ?>-merge" data-offcanvas="addons" class="offcanvas pop-waypoints-context scrollable addons perfect-scrollbar vertical pop-merge <?php echo implode(' ', $addons_templates); ?>">
		<div class="window-controls">
			<a id="<?php echo GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_ADDONS, 'window-fullsize') ?>" data-target="#<?php echo $addons_window_id ?>" data-toggle="window-fullsize" href="#" class="fullsize close <?php /*hidden-xs*/ ?>" title="<?php _e('Full size', 'poptheme-wassup') ?>"><span class="glyphicon glyphicon-resize-full"></span></a>
			<a id="<?php echo GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_ADDONS, 'window-maximize') ?>" data-target="#<?php echo $addons_window_id ?>" data-toggle="window-maximize" href="#" class="maximize close" title="<?php _e('Maximize', 'poptheme-wassup') ?>"><span class="glyphicon glyphicon-resize-small"></span></a>
			<a id="<?php echo GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_ADDONS, 'window-minimize') ?>" data-target="#<?php echo $addons_window_id ?>" data-toggle="window-minimize" href="#" class="minimize close" title="<?php _e('Minimize', 'poptheme-wassup') ?>"><span class="glyphicon glyphicon-minus"></span></a>
		</div>		
		<div class="container-fluid perfect-scrollbar-offsetreference">
			<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONS ?>-merge"></div>
		</div>
	</div>
	<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONTABS ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_ADDONS ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONTABS ?>-merge" data-offcanvas="addontabs" class="offcanvas pop-waypoints-context scrollable addontabs perfect-scrollbar horizontal pop-merge <?php echo implode(' ', $addontabs_templates); ?> navbar navbar-main navbar-addons">
		<div class="perfect-scrollbar-offsetreference">
			<div class="pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_ADDONTABS ?>-merge"></div>
		</div>
	</div>
</div>