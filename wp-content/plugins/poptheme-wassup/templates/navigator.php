<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_NAVIGATOR ?>" data-clickframetarget="<?php echo GD_URLPARAM_TARGET_MAIN ?>" data-pagesection-openmode="automatic" data-merge-container="#<?php echo GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR ?>-merge" data-offcanvas="navigator" class="offcanvas pop-waypoints-context scrollable navigator perfect-scrollbar vertical pop-merge <?php echo GD_TEMPLATE_PAGESECTION_NAVIGATOR ?>">
	<div class="container-fluid perfect-scrollbar-offsetreference">
		<div class="clearfix">
			<a id="<?php echo GD_TemplateManager_Utils::get_frontend_id(GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR, 'closenavigator') ?>" href="#" class="toggle-side close" title="<?php _e('Close', 'poptheme-wassup') ?>" data-target="#<?php echo GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR ?>" data-toggle="offcanvas-close">
				<span class="glyphicon glyphicon-remove"></span>
			</a>
		</div>
		<div class="tab-content pop-merge-target" id="<?php echo GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR ?>-merge"><?php echo PoP_ServerSideRendering_Utils::render_pagesection(GD_TEMPLATE_PAGESECTION_NAVIGATOR, GD_URLPARAM_TARGET_NAVIGATOR) ?></div>
	</div>
</div>