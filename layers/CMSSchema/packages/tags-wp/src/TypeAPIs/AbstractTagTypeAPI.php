<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use PoP\Root\App;
use WP_Error;
use WP_Post;
use WP_Term;

use function get_tags;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends AbstractTaxonomyTypeAPI implements TagTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    protected function getTaxonomyName(): string
    {
        return $this->getTagTaxonomyName();
    }

    abstract protected function getTagTaxonomyName(): string;

    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        return $this->isInstanceOfTaxonomyType($object);
    }

    protected function isHierarchical(): bool
    {
        return false;
    }

    protected function getTagFromObjectOrID(string|int|object $tagObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID($tagObjectOrID);
    }

    public function getTagName(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermName($tagObjectOrID);
    }

    public function getTag(string|int $tagID): ?object
    {
        return $this->getTaxonomyTerm($tagID);
    }

    public function getTagByName(string $tagName): ?object
    {
        return $this->getTaxonomyTermByName($tagName);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,int>|object[]
     */
    public function getCustomPostTags(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTerms(
            $customPostObjectOrID, 
            $query,
            $options,
        );
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTagCount(string|int|object $customPostObjectOrID, array $query = [], array $options = []): int
    {
        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTermCount(
            $customPostObjectOrID,
            $query,
            $options,
        ) ?? 0;
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount(array $query = [], array $options = []): int
    {
        return $this->getTaxonomyCount($query, $options);
    }

    /**
     * @return array<string,int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags(array $query, array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);
        $tags = get_tags($query);
        if ($tags instanceof WP_Error) {
            return [];
        }
        /** @var object[] */
        return $tags;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function convertTagsQuery(array $query, array $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    public function getTagURL(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURL($tagObjectOrID);
    }

    public function getTagURLPath(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURLPath($tagObjectOrID);
    }

    public function getTagSlug(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermSlug($tagObjectOrID);
    }

    public function getTagDescription(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermDescription($tagObjectOrID);
    }

    public function getTagItemCount(string|int|object $tagObjectOrID): ?int
    {
        /** @var string|int|WP_Term $tagObjectOrID */
       return $this->getTaxonomyTermItemCount($tagObjectOrID);
    }

    public function getTagID(object $tag): string|int
    {
        /** @var WP_Term $tag */
        return $this->getTaxonomyTermID($tag);
    }
}
