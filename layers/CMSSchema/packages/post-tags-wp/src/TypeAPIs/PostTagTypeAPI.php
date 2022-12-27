<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagsWP\TypeAPIs;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI extends AbstractTagTypeAPI implements PostTagTypeAPIInterface
{
    /**
     * The taxonomy name representing a post tag ("post_tag")
     */
    public function getPostTagTaxonomyName(): string
    {
        return 'post_tag';
    }

    protected function getTagTaxonomyName(): string
    {
        return $this->getPostTagTaxonomyName();
    }
}
