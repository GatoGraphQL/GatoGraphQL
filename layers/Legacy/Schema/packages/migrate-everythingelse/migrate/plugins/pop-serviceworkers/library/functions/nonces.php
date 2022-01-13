<?php

// Comment Leo 06/08/2017: no, it is not needed! A solution is to bring the nonce not when creating the editor, but when logging in! Then,
// we can even keep the user_id as part of the nonce!!!!!
// // Comment Leo 06/08/2017: actually yes, it is needed, because the /add-post/ page is not brought fresh from the server,
// // but it is cached on the /initial-frames/ in the localStorage
// // // Comment Leo 06/08/2017: No need for this anymore, since retrieving the nonce in the topLevelFeedback for each request,
// // // and sending this updated nonce value when uploading media
// /**
//  * Changes from PoP:
//  * If enabling the Service Workers and caching the html, then we can't enable the nonce control anymore,
//  * since that value will always be stale and give back the value false. At least for the current nonce of just 1 day
//  * So make the nonce a tiny bit longer
//  */
// \PoP\Root\App::getHookManager()->addFilter('nonce_life', 'popSwNonceLife');
// function popSwNonceLife($nonce_life) {

//     // 180 Days: this implies that the Service Workers cache must be regenerated every, at most, 180 days,
//     // or otherwise the website will fail. Eg: upload images to the media gallery will fail
//     return 180*DAY_IN_SECONDS;
// }
