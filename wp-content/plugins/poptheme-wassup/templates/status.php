<?php 
	$status = 
		'<div id="%1$s-status">'.
			'<div class="pop-loading top-level hidden alert alert-warning alert-sm">'.
				GD_CONSTANT_LOADING_SPINNER.' '.GD_CONSTANT_LOADING_MSG.
				' <small><span class="pop-box"></span></small>'.
			'</div>'.
			'<div id="%1$s-status-error" class="pop-error top-level hidden alert alert-warning alert-sm">'.
				'<button type="button" class="close" aria-hidden="true" onclick="document.getElementById(\'%1$s-status-error\').className += \' hidden\';">&times;</button>'.
				'<div class="pop-box"></div>'.
			'</div>'.
		'</div>';

	$mainpagesection_status = sprintf(
		$status,
		GD_TEMPLATEID_PAGESECTIONID_MAIN
	);
	$quickview_status = sprintf(
		$status,
		GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN
	);
	$navigator_status = sprintf(
		$status,
		GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR
	);
?>
<?php echo $mainpagesection_status ?>
<?php echo $quickview_status ?>
<?php echo $navigator_status ?>
