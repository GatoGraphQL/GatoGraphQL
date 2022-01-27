<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface PostTagTypeAPIInterface extends TagTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type PostTag
     */
    public function isInstanceOfPostTagType(object $object): bool;

    /**
     * The taxonomy name representing a post tag ("post_tag")
     */
    public function getPostTagTaxonomyName(): string;
}
