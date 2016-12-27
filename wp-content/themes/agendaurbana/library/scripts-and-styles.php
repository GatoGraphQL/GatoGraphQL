<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('pop_header_inlinestyles:styles', 'agendaurbana_inlinestyles');
function agendaurbana_inlinestyles($styles) {

	$styles .= '
		body.sliding .background-screen,
		body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		  background: #eff8fb url('.AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-bg-1440x900.png) no-repeat fixed center;
		}
		@media (min-width: 1440px) {
		  body.sliding .background-screen,
		  body.sliding.pop-loadingframe.pop-loadingjs .loading-screen {
		    background-image: url('.AGENDAURBANA_ASSETS_URI.'/img/agendaurbana-bg-1920x1080.png);
		  }
		}
	';
	return $styles;
}
