<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popUsercommunitiesJqueryConstants');
function popUsercommunitiesJqueryConstants($jqueryConstants)
{
    $jqueryConstants['ROLES'] = gdRoles();
    return $jqueryConstants;
}
