<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcms:excerptMore', 'gdExcerptMore', 10000, 1);
function gdExcerptMore($excerpt_more)
{
    return '...';
}
