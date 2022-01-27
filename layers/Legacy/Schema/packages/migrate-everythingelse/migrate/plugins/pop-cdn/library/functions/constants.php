<?php

define('GD_URLPARAM_CDNTHUMBPRINT', 'tp');
define('POP_CDN_THUMBPRINTVALUES', 'tpv');
define('POP_CDN_SEPARATOR_THUMBPRINT', '.');

\PoP\Root\App::addFilter('gd_jquery_constants', 'popCdnJqueryConstants');
function popCdnJqueryConstants($jqueryConstants)
{
    if (POP_CDNFOUNDATION_CDN_CONTENT_URI) {
        $jqueryConstants['CDN_URLPARAM_THUMBPRINT'] = GD_URLPARAM_CDNTHUMBPRINT;
        $jqueryConstants['CDN_THUMBPRINTVALUES'] = POP_CDN_THUMBPRINTVALUES;
        $jqueryConstants['CDN_SEPARATOR_THUMBPRINT'] = POP_CDN_SEPARATOR_THUMBPRINT;
    }
    return $jqueryConstants;
}
