<?php

declare(strict_types=1);

namespace PoPSchema\PostTagsWP\TypeAPIs;

use PoPSchema\TagsWP\TypeAPIs\TagTypeAPI;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI extends TagTypeAPI implements PostTagTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfPostTagType($object): bool
    {
        return $this->isInstanceOfPostTagType($object) && $object->name = $this->getPostTagTaxonomyName();
    }

    /**
     * The taxonomy name representing a post tag ("post_tag")
     *
     * @return string
     */
    public function getPostTagTaxonomyName(): string
    {
        return 'post_tag';
    }
}
