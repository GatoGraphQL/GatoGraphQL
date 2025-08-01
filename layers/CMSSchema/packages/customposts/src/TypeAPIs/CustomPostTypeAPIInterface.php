<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type (Generic)CustomPost
     */
    public function isInstanceOfCustomPostType(object $object): bool;
    /**
     * Indicate if an post with provided ID exists
     */
    public function customPostExists(int|string $id): bool;
    /**
     * Return the object's ID
     */
    public function getID(object $customPostObject): string|int;
    public function getContent(string|int|object $customPostObjectOrID): ?string;
    public function getRawContent(string|int|object $customPostObjectOrID): ?string;
    public function getPermalink(string|int|object $customPostObjectOrID): ?string;
    public function getPermalinkPath(string|int|object $customPostObjectOrID): ?string;
    public function getSlug(string|int|object $customPostObjectOrID): ?string;
    public function getSlugPath(string|int|object $customPostObjectOrID): ?string;
    public function getStatus(string|int|object $customPostObjectOrID): ?string;
    public function getPublishedDate(string|int|object $customPostObjectOrID, bool $gmt = false): ?string;
    public function getModifiedDate(string|int|object $customPostObjectOrID, bool $gmt = false): ?string;
    public function getTitle(string|int|object $customPostObjectOrID): ?string;
    public function getRawTitle(string|int|object $customPostObjectOrID): ?string;
    public function getExcerpt(string|int|object $customPostObjectOrID): ?string;
    public function getRawExcerpt(string|int|object $customPostObjectOrID): ?string;
    /**
     * Get the custom post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int|string $id): ?object;
    public function getCustomPostType(string|int|object $customPostObjectOrID): ?string;
    public function getCustomPostParentID(string|int|object $customPostObjectOrID): int|string|null;
    /**
     * Get the list of ancestor IDs (parent, grandparent, etc.)
     *
     * @return array<int|string>|null
     */
    public function getCustomPostAncestorIDs(string|int|object $customPostObjectOrID): ?array;
    /**
     * Get the custom post with provided slug path or, if it doesn't exist, null
     *
     * @param string $slugPath The slug path (including all ancestor slugs, eg: "ancestor-slug/post-slug")
     * @param string $customPostType The custom post type to search in
     */
    public function getCustomPostBySlugPath(string $slugPath, string $customPostType): ?object;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     *
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPosts(array $query, array $options = []): array;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCount(array $query, array $options = []): int;
    /**
     * @return string[]
     * @param array<string,mixed> $query
     */
    public function getCustomPostTypes(array $query = array()): array;
}
