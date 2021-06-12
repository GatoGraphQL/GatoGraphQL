<?php
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

define('POP_CDN_THUMBPRINT_TAG', 'tag');

class PoP_CDN_Thumbprint_Tag extends PoP_CDN_ThumbprintBase
{
    public function getName(): string
    {
        return POP_CDN_THUMBPRINT_TAG;
    }

    public function getQuery()
    {
        return array(
            // 'fields' => 'ids',
            'limit' => 1,
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:tags:id'),
            'order' => 'DESC',
        );
    }

    public function executeQuery($query, array $options = [])
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $options['return-type'] = ReturnTypes::IDS;
        return $postTagTypeAPI->getTags($query, $options);
    }

    public function getTimestamp($tag_id)
    {

        // Because tags never change, and the only activity for them is to have a new tag created,
        // then returning the tag_id of the last created tag is already enough
        return $tag_id;
    }
}

/**
 * Initialize
 */
new PoP_CDN_Thumbprint_Tag();
