<?php

\PoP\Root\App::addFilter(
    'excerpt_more',// Must add a loose contract instead: 'popcms:excerptMore'
    'gdExcerptMore',
    10000,
    1
);
function gdExcerptMore($excerpt_more)
{
    return '...';
}
