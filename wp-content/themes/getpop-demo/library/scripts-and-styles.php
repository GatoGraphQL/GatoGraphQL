<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('PoPThemeWassup_FileReproduction_BackgroundImage:props', 'getpopdemo_bgprops');
function getpopdemo_bgprops($props) {

	return array(
        'color' => '#fcec8c',
        'urls' => array(
            '1440x900' => GETPOPDEMO_ASSETS_URL.'/img/getpop-demo-bg-1440x900-2.png',
            '1920x1080' => GETPOPDEMO_ASSETS_URL.'/img/getpop-demo-bg-1920x1080-2.png',
        ),
    );
}
// add_filter('pop_header_inlinestyles:styles', 'getpopdemo_inlinestyles');
// function getpopdemo_inlinestyles($styles) {

// 	$styles .= '
// 		@media screen {
// 			body.sliding,
// 			body.embed #background-screen {
// 			  background: #fcec8c url('.GETPOPDEMO_ASSETS_URL.'/img/getpop-demo-bg-1440x900-2.png) no-repeat fixed center;
// 			  background-size: cover;
// 			}
// 		}
// 		@media screen and (min-width: 1440px), screen and (min-height: 900px) {
// 			body.sliding,
// 			body.embed #background-screen {
// 			  background-image: url('.GETPOPDEMO_ASSETS_URL.'/img/getpop-demo-bg-1920x1080-2.png);
// 			}
// 		}
// 	';

// 	return $styles;
// }
