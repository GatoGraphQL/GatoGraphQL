<?php 
$pagesection_templates = array(
	GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME, 
	GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG, 
	GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE, 
	GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE, 
	GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR, 
	GD_TEMPLATE_PAGESECTION_QUICKVIEW404
); 
$sideinfo_templates = array(
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE,
	GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME,
);
?>
<div class="modal" id="quickview-modal" tabindex="-1" role="dialog" aria-labelledby="quickview" aria-hidden="true">
	<div class="modal-dialog <?php /*modal-lg*/?>">
		<div class="buttons clearfix" id="quickview-modal-buttons">
			<button type="button" class="close close-lg" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove"></span></button>
		</div>
		<div class="modal-content">
			<div class="modal-body">
				<div id="<?php echo GD_TEMPLATEID_QUICKVIEWPAGESECTIONGROUP_ID ?>" class="pop-pagesection-group quickviewpagesection-group row">
					<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_QUICKVIEW ?>" data-offcanvas="main" class="offcanvas main tab-content pop-merge  <?php echo GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN ?> <?php echo implode(' ', $pagesection_templates); ?>"><?php echo PoP_ServerSideRendering_Factory::get_instance()->render_pagesection(GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN, GD_URLPARAM_TARGET_QUICKVIEW) ?></div>
					<div id="<?php echo GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO ?>" data-frametarget="<?php echo GD_URLPARAM_TARGET_QUICKVIEW ?>" data-offcanvas="sideinfo" data-pagesection-openmode="manual" class="offcanvas sideinfo tab-content pop-merge <?php echo GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO ?> <?php echo implode(' ', $sideinfo_templates); ?>"><?php echo PoP_ServerSideRendering_Factory::get_instance()->render_pagesection(GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO, GD_URLPARAM_TARGET_QUICKVIEW) ?></div>
				</div>
			</div>
		</div>
	</div>
</div>