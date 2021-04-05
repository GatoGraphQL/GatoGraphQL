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
     */
    public function isInstanceOfPostTagType(object $object): bool
    {
        return $this->isInstanceOfPostTagType($object) && $object->name = $this->getPostTagTaxonomyName();
    }

    /**
     * The taxonomy name representing a post tag ("post_tag")
     */
    public function getPostTagTaxonomyName(): string
    {
        return 'post_tag';
    }

    public function getTaxonomyName(): string
    {
        return $this->getPostTagTaxonomyName();
    }

    public function getTagBaseOption(): string
    {
        return 'tag_base';
    }
}
