<?php

\PoP\Root\App::addFilter('popcms:excerptMore', 'gdExcerptMore', 10000, 1);
function gdExcerptMore($excerpt_more)
{
    return '...';
}
