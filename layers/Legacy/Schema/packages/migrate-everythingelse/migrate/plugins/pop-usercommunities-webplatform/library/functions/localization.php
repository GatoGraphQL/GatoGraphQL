<?php

\PoP\Root\App::addFilter('gd_jquery_constants', 'popUsercommunitiesJqueryConstants');
function popUsercommunitiesJqueryConstants($jqueryConstants)
{
    $jqueryConstants['ROLES'] = gdRoles();
    return $jqueryConstants;
}
