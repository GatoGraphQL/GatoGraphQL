<div id="<?php echo GD_TEMPLATEID_PAGESECTIONGROUP_ID ?>" class="pop-pagesection-group pagesection-group <?php echo implode(' ', PoPTheme_Wassup_Utils::get_pagesectiongroup_active_pagesection_classes(array('active-top'))) ?>">
	<header class="header">
		<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_TOP ?>" data-offcanvas="top" class="offcanvas frame top topnav pop-merge <?php echo GD_TEMPLATE_PAGESECTION_TOPEMBED ?> navbar navbar-main navbar-inverse" role="navigation"><?php echo PoP_ServerSideRendering_Utils::render_pagesection(GD_TEMPLATE_PAGESECTION_TOPEMBED) ?></div>	
	</header>