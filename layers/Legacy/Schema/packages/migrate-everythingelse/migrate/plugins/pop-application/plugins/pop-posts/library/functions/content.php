<?php

\PoP\Root\App::getHookManager()->addFilter('popcms:excerptMore', 'gdExcerptMore', 10000, 1);
function gdExcerptMore($excerpt_more)
{
    return '...';
}
