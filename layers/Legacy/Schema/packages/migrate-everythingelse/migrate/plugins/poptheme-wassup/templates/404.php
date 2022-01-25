<?php

if (defined('POP_ENGINEHTMLCSSPLATFORM_INITIALIZED')) {
    // Taken from http://wpexpert.com.au/archives/how-to-correctly-add-a-404-error-image-to-your-wordpress-theme/
    // This way 404 error images do not slow down loading the website
    //If they have tried to access an invalid image file, return a 404 image
    if (preg_match('~\.(jpe?g|png|gif|svg|bmp)(\?.*)?$~i', \PoP\Root\App::server('REQUEST_URI'))) {
        // $image404 = locate_template(POPTHEME_WASSUP_DIR.'/img/404.png');
        $image404 = POPTHEME_WASSUP_DIR.'/img/404.png';
        if (!is_null($image404)) {
            $fp = fopen($image404, 'rb');
            header('Content-Type: image/png');
            header('Content-Length: ' . filesize($image404));
            fpassthru($fp);
        }
        exit;
    }

    include POP_ENGINEHTMLCSSPLATFORM_TEMPLATES.'/index.php';
} else {
    include POP_ENGINE_TEMPLATES.'/index.php';
}
