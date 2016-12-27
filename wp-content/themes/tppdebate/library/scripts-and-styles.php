<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'tppdebate_inlinestyles');
function tppdebate_inlinestyles($styles) {

	$styles .= '
		body.sliding .background-screen,
		body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		  background: #edf9f7 url('.TPPDEBATE_ASSETS_URI.'/img/tppdebate-bg-1440x900.png) no-repeat fixed center;
		}
		@media (min-width: 1440px) {
		  body.sliding .background-screen,
		  body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		    background-image: url('.TPPDEBATE_ASSETS_URI.'/img/tppdebate-bg-1920x1080.png);
		  }
		}
	';
	return $styles;
}
