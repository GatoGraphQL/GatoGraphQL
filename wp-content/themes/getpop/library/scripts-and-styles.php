<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'getpop_inlinestyles');
function getpop_inlinestyles($styles) {

	if (GetPoP_Utils::is_demo()) {

		// $styles .= '
		// 	body.sliding.getpop-demo'/*body.sliding.getpop-demo .background-screen,
		// 	body.sliding.getpop-demo .loading-screen,
		// 	body.sliding.pop-loadingframe.pop-loadingjs.getpop-demo .loading-screen*/.' {
		// 	  background: #fcec8c url('.GETPOP_ASSETS_URI.'/img/getpop-demo-bg-1440x900.jpg) no-repeat fixed center;
		// 	}
		// 	@media (min-width: 1440px) {
		// 	  body.sliding.getpop-demo'/*body.sliding.getpop-demo .background-screen,
		// 	  body.sliding.getpop-demo .loading-screen*/.' { 
		// 	    background-image: url('.GETPOP_ASSETS_URI.'/img/getpop-demo-bg-1920x1080.jpg);
		// 	  }
		// 	}
		// ';
		$styles .= '
			body.sliding.getpop-demo {
			  background: #fcec8c url('.GETPOP_ASSETS_URI.'/img/getpop-demo-bg-1920x1080.jpg) no-repeat fixed center;
			}
		';
	}
	else {

		// $styles .= '
		// 	body.sliding.getpop'/*body.sliding.getpop .background-screen,
		// 	body.sliding.getpop .loading-screen,
		// 	body.sliding.pop-loadingframe.pop-loadingjs.getpop .loading-screen*/.' {
		// 	  background: #a2dfe4 url('.GETPOP_ASSETS_URI.'/img/getpop-bg-1440x900.jpg) no-repeat fixed center;
		// 	}
		// 	@media (min-width: 1440px) {
		// 	  body.sliding.getpop'/*body.sliding.getpop .background-screen,
		// 	  body.sliding.getpop .loading-screen*/.' {
		// 	    background-image: url('.GETPOP_ASSETS_URI.'/img/getpop-bg-1920x1080.jpg);
		// 	  }
		// 	}
		// ';
		$styles .= '
			body.sliding.getpop {
			  background: #a2dfe4 url('.GETPOP_ASSETS_URI.'/img/getpop-bg-1920x1080.jpg) no-repeat fixed center;
			}
		';
	}
	return $styles;
}
