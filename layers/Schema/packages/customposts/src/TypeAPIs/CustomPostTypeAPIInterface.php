<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeAPIs;

use PoPSchema\CustomPosts\Types\CustomPostTypeInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeAPIInterface extends CustomPostTypeInterface
{
    /**
     * Get the custom post with provided ID or, if it doesn't exist, null
     */
    public function getCustomPost(int | string $id): ?object;
    public function getCustomPostType(string | int | object $objectOrID): string;
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function getCustomPosts(array $query, array $options = []): array;
    public function getCustomPostCount(array $query = [], array $options = []): int;
    public function getCustomPostTypes(array $query = array()): array;
}
