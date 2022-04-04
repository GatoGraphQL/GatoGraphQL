<?php

\PoP\Root\App::addFilter('gd_jquery_constants', popUserplatformJqueryConstants(...));
function popUserplatformJqueryConstants($jqueryConstants)
{
    $jqueryConstants['USERATTRIBUTES'] = gdUserAttributes();
    return $jqueryConstants;
}
