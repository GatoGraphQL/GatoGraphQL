<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TagTypeAPIInterface extends TaxonomyTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool;

    public function getTagID(object $tag): string|int;
    public function getTag(string|int $tagID): object;
    public function getTagByName(string $tagName): object;
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags(array $query, array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount(array $query = [], array $options = []): int;
    public function getTagURL(string|int|object $tagObjectOrID): string;
    public function getTagURLPath(string|int|object $tagObjectOrID): string;
    public function getTagName(string|int|object $tagObjectOrID): string;
    public function getTagSlug(string|int|object $tagObjectOrID): string;
    public function getTagDescription(string|int|object $tagObjectOrID): string;
    public function getTagItemCount(string|int|object $tagObjectOrID): int;
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTags(string|int $customPostID, array $query = [], array $options = []): array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTagCount(string|int $customPostID, array $query = [], array $options = []): int;
}
