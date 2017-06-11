<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_HOVER ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_HOVER ?>-merge" data-offcanvas="hover" class="offcanvas pop-waypoints-context scrollable hover perfect-scrollbar vertical pop-merge <?php echo GD_TEMPLATE_PAGESECTION_HOVER ?>">
	<div class="container-fluid perfect-scrollbar-offsetreference">
		<div class="clearfix">
			<a id="<?php echo GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_HOVER, 'closehover') ?>" href="#" class="toggle-side close close-lg" title="<?php _e('Close', 'poptheme-wassup') ?>" data-target="#<?php echo GD_TEMPLATEID_PAGESECTIONID_HOVER ?>" data-toggle="offcanvas-close">
				<span class="glyphicon glyphicon-remove"></span>
			</a>
		</div>
		<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_HOVER ?>-merge"><?php echo PoP_ServerSideRendering_Factory::get_instance()->render_pagesection(GD_TEMPLATE_PAGESECTION_HOVER) ?></div>
	</div>
</div>