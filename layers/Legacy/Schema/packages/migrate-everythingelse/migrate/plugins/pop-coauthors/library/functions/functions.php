<?php
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Override function to get the authors of a post
 */
\PoP\Root\App::addFilter('gdGetPostauthors', 'gdGdGetPostauthors', 10, 2);
function gdGdGetPostauthors($authors, $post_id)
{
    $pluginapi = PoP_Coauthors_APIFactory::getInstance();
    return $pluginapi->getCoauthors($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
}
