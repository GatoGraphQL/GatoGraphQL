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
     * The taxonomy name representing a post tag ("post_tag")
     */
    public function getPostTagTaxonomyName(): string;
    /**
     * Return all the tag taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getRegisteredPostTagTaxonomyNames(): array;
}
