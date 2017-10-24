<?php

// The target where to add the preview is the Quickview
add_filter('gd_ppp_previewurl_target', 'popthemewassup_ppp_previewurl_target');
function popthemewassup_ppp_previewurl_target($target) {

	return GD_URLPARAM_TARGET_QUICKVIEW;
}
