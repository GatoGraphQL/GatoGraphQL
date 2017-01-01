<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'mesym_inlinestyles');
function mesym_inlinestyles($styles) {

	$styles .= '
		body.sliding .background-screen,
		body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		  background: #eff4c1 url('.MESYM_ASSETS_URI.'/img/mesym-bg-1440x900.jpg?ver=1) no-repeat fixed center;
		}
		@media (min-width: 1440px) {
		  body.sliding .background-screen,
		  body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		    background-image: url('.MESYM_ASSETS_URI.'/img/mesym-bg-1920x1080.jpg?ver=1);
		  }
		}
	';
	return $styles;
}
