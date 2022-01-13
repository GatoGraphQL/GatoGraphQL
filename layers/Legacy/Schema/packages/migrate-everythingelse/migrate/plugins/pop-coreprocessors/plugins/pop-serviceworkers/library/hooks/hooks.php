<?php

\PoP\Root\App::getHookManager()->addFilter('GD_Core_Module_Processor_Blocks:jsmethod:latestcounts', 'popSwLatestcountsjsmethodsResettimestamp');
function popSwLatestcountsjsmethodsResettimestamp($jsmethods)
{
    $jsmethods[] = 'resetTimestamp';
    return $jsmethods;
}
