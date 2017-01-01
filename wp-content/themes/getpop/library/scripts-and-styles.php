<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'getpop_inlinestyles');
function getpop_inlinestyles($styles) {

	if (GetPoP_Utils::is_demo()) {

		$styles .= '
			body.sliding.getpop-demo .background-screen,
			body.sliding.getpop-demo .loading-screen {
			  background: #fcf9eb url('.GETPOP_ASSETS_URI.'/img/getpop-demo-bg-1440x900.jpg?ver=1) no-repeat fixed center;
			}
			@media (min-width: 1440px) {
			  body.sliding.getpop-demo .background-screen,
			  body.sliding.getpop-demo .loading-screen { 
			    background-image: url('.GETPOP_ASSETS_URI.'/img/getpop-demo-bg-1920x1080.jpg?ver=1);
			  }
			}
		';
	}
	else {

		$styles .= '
			body.sliding.getpop .background-screen,
			body.sliding.getpop .loading-screen {
			  background: #babfc4 url('.GETPOP_ASSETS_URI.'/img/getpop-bg-1440x900.jpg) no-repeat fixed center;
			}
			@media (min-width: 1440px) {
			  body.sliding.getpop .background-screen,
			  body.sliding.getpop .loading-screen {
			    background-image: url('.GETPOP_ASSETS_URI.'/img/getpop-bg-1920x1080.jpg);
			  }
			}
		';
	}
	return $styles;
}
