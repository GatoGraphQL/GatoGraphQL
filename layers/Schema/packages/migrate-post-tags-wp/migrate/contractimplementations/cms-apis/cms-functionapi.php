<?php

namespace PoPSchema\PostTags\WP;

use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class FunctionAPI extends \PoPSchema\Tags\WP\AbstractFunctionAPI implements \PoPSchema\PostTags\FunctionAPI
{
    public function __construct()
    {
        \PoPSchema\PostTags\FunctionAPIFactory::setInstance($this);
    }

    /**
     * Implement this function by the actual service
     *
     * @return string
     */
    protected function getTaxonomyName(): string
    {
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        return $postTagTypeAPI->getPostTagTaxonomyName();
    }
    /**
     * Implement this function by the actual service
     *
     * @return string
     */
    protected function getTagBaseOption(): string
    {
        return 'tag_base';
    }
}

/**
 * Initialize
 */
new FunctionAPI();
