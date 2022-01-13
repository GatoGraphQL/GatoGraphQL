<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcms:excerptMore', 'gdExcerptMore', 10000, 1);
function gdExcerptMore($excerpt_more)
{
    return '...';
}
