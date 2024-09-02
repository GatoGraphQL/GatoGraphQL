<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;
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
abstract class AbstractTagTypeAPI extends AbstractTaxonomyTypeAPI implements TagTypeAPIInterface, TagListTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    abstract protected function getTagTaxonomyName(): string;
    
    /**
     * @return string[]
     */
    abstract protected function getTagTaxonomyNames(): array;

    protected function isTagTaxonomy(WP_Term $taxonomyTerm): bool
    {
        return in_array($taxonomyTerm->taxonomy, $this->getTagTaxonomyNames());
    }

    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        return $this->isTagTaxonomy($object);
    }

    protected function isHierarchical(): bool
    {
        return false;
    }

    protected function getTagFromObjectOrID(string|int|object $tagObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    protected function getTaxonomyTermFromObjectOrID(
        string|int|WP_Term $taxonomyTermObjectOrID,
        string $taxonomy = '',
    ): ?WP_Term {
        $taxonomyTerm = parent::getTaxonomyTermFromObjectOrID(
            $taxonomyTermObjectOrID,
            $taxonomy,
        );
        if ($taxonomyTerm === null) {
            return $taxonomyTerm;
        }
        /** @var WP_Term $taxonomyTerm */
        return $this->isTagTaxonomy($taxonomyTerm) ? $taxonomyTerm : null;
    }

    public function getTagName(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermName(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTag(string|int $tagID): ?object
    {
        $tag = $this->getTaxonomyTerm(
            $tagID,
            $this->getTagTaxonomyName(),
        );
        if ($tag === null) {
            return null;
        }
        /** @var WP_Term $tag */
        if (!$this->isTagTaxonomy($tag)) {
            return null;
        }
        return $tag;
    }

    public function tagExists(int|string $id): bool
    {
        return $this->getTag($id) !== null;
    }

    public function getTagByName(string $tagName): ?object
    {
        $tag = $this->getTaxonomyTermByName(
            $tagName,
            $this->getTagTaxonomyName(),
        );
        if ($tag === null) {
            return null;
        }
        /** @var WP_Term $tag */
        return $this->isTagTaxonomy($tag) ? $tag : null;
    }

    /**
     * @param string|int|WP_Post $customPostObjectOrID
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPostTags(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyName();
        }

        /** @var array<string|int>|object[] */
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
    public function getCustomPostTagCount(string|int|object $customPostObjectOrID, array $query = [], array $options = []): ?int
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyName();
        }

        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTermCount(
            $customPostObjectOrID,
            $query,
            $options,
        );
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount(array $query = [], array $options = []): int
    {
        /** @var int */
        return $this->getTaxonomyCount($query, $options);
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags(array $query, array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);

        // If passing an empty array to `filter.ids`, return no results
        if ($this->isFilteringByEmptyArray($query)) {
            return [];
        }

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
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { tags(taxonomy: nav_menu) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyNames();
        }
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
    final public function convertTagsQuery(array $query, array $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    public function getTagURL(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURL(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTagURLPath(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURLPath(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTagSlug(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermSlug(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTagDescription(string|int|object $tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermDescription(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTagItemCount(string|int|object $tagObjectOrID): ?int
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermItemCount(
            $tagObjectOrID,
            $this->getTagTaxonomyName(),
        );
    }

    public function getTagID(object $tag): string|int
    {
        /** @var WP_Term $tag */
        return $this->getTaxonomyTermID($tag);
    }
}
