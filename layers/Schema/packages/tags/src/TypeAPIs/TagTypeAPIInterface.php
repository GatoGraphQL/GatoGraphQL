<?php

declare(strict_types=1);

namespace PoPSchema\Tags\TypeAPIs;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool;
    public function getCustomPostTags($post_id, array $query = [], array $options = []): array;
    public function getCustomPostTagCount($post_id, array $query = [], array $options = []): int;
    public function getTag($tag_id);
    public function getTagByName($tag_name);
    public function getTags($query, array $options = []): array;
    public function getTagCount(array $query = [], array $options = []): int;
    public function getTagLink($tag_id);
    public function getTagName($tag_id);
    public function getTagBase();
    public function setPostTags($post_id, array $tags, bool $append = false);
}
