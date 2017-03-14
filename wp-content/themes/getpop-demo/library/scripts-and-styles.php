<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'getpopdemo_inlinestyles');
function getpopdemo_inlinestyles($styles) {

	// $styles .= '
	// 	body.sliding.getpop-demo'/*body.sliding.getpop-demo .background-screen,
	// 	body.sliding.getpop-demo .loading-screen,
	// 	body.sliding.pop-loadingframe.pop-loadingjs.getpop-demo .loading-screen*/.' {
	// 	  background: #fcec8c url('.GETPOPDEMO_ASSETS_URI.'/img/getpop-demo-bg-1440x900.jpg) no-repeat fixed center;
	// 	}
	// 	@media (min-width: 1440px) {
	// 	  body.sliding.getpop-demo'/*body.sliding.getpop-demo .background-screen,
	// 	  body.sliding.getpop-demo .loading-screen*/.' { 
	// 	    background-image: url('.GETPOPDEMO_ASSETS_URI.'/img/getpop-demo-bg-1920x1080.jpg);
	// 	  }
	// 	}
	// ';
	$styles .= '
		body.sliding,
		body.embed #background-screen {
		  background: #fcec8c url('.GETPOPDEMO_ASSETS_URI.'/img/getpop-demo-bg-1920x1080-2.png) no-repeat fixed center;
		}
	';

	return $styles;
}
