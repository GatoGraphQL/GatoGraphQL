<?php

declare(strict_types=1);

namespace PoPSchema\PostTagsWP\TypeAPIs;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI extends AbstractTagTypeAPI implements PostTagTypeAPIInterface
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

    public function getTagTaxonomyName(): string
    {
        return $this->getPostTagTaxonomyName();
    }
}
