<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeAPIInterface
{
    /**
     * Return the object's ID
     */
    public function getID(object $customPostObject): string | int;
    public function getContent(string | int | object $customPostObjectOrID): ?string;
    public function getRawContent(string | int | object $customPostObjectOrID): ?string;
    public function getPermalink(string | int | object $customPostObjectOrID): ?string;
    public function getPermalinkPath(string | int | object $customPostObjectOrID): ?string;
    public function getSlug(string | int | object $customPostObjectOrID): ?string;
    public function getStatus(string | int | object $customPostObjectOrID): ?string;
    public function getPublishedDate(string | int | object $customPostObjectOrID, bool $gmt = false): ?string;
    public function getModifiedDate(string | int | object $customPostObjectOrID, bool $gmt = false): ?string;
    public function getTitle(string | int | object $customPostObjectOrID): ?string;
    public function getExcerpt(string | int | object $customPostObjectOrID): ?string;
    /**
     * Get the custom post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int | string $id): ?object;
    public function getCustomPostType(string | int | object $objectOrID): string;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     *
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return object[]
     */
    public function getCustomPosts(array $query, array $options = []): array;
    /**
     * If param "status" in $query is not passed, it defaults to "publish"
     */
    public function getCustomPostCount(array $query = [], array $options = []): int;
    public function getCustomPostTypes(array $query = array()): array;
}
