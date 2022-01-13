<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

// Comment Leo 10/08/2017: we need to allow other websites to embed our content:
// Multidomain (so we can, in GetPoP, display a page from MESYM with an iframe, such as the post-link posts)
// Otherwise, after logging in, we can't load iframes anymore
\PoP\Root\App::getHookManager()->removeAction('login_init', 'send_frame_options_header', 10, 0);
\PoP\Root\App::getHookManager()->removeAction('admin_init', 'send_frame_options_header', 10, 0);

// \PoP\Root\App::getHookManager()->addAction('send_headers', 'gdWpHeaders');
// function gdWpHeaders() {

//     header('Access-Control-Allow-Origin: *');
// }

// // Comment Leo 07/12/2016: Since switching to the Server with the Let's Encrypt cert, somehow the headers are sent as:
// // X-Frame-Options:DENY
// // So https://demo.getpop.org produces the following error:
// // Refused to display 'https://getpop.org/es/blog/?thememode=embed&format=list' in a frame because it set 'X-Frame-Options' to 'DENY'.
// // It should be set to SAMEORIGIN, so here explicitly send it again
// // \PoP\Root\App::getHookManager()->addAction('send_headers', 'send_frame_options_header', 10, 0);
// \PoP\Root\App::getHookManager()->addAction('send_headers', 'popSendFrameOptionsHeader', 10, 0);
// function popSendFrameOptionsHeader() {
//     @header( 'X-Frame-Options: SAMEORIGIN' );
// }
