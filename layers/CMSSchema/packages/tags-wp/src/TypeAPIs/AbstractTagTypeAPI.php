<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractCustomPostTaxonomyTypeAPI;
use PoP\Root\App;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

use function get_tag;
use function get_tags;
use function get_term_by;
use function get_term_link;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends AbstractCustomPostTaxonomyTypeAPI implements TagTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    private ?CMSHelperServiceInterface $cmsHelperService = null;

    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    protected function getCustomPostTaxonomyName(): string
    {
        return $this->getTagTaxonomyName();
    }

    abstract protected function getTagTaxonomyName(): string;

    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        if (!$this->isInstanceOfTaxonomyType($object)) {
            return false;
        }
        /** @var WP_Taxonomy $object */
        return !$object->hierarchical;
    }

    protected function getTagFromObjectOrID(string|int|object $tagObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $catObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID($tagObjectOrID);
    }
    public function getTagName(string|int|object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        if ($tag === null) {
            return '';
        }
        /** @var WP_Term $tag */
        return $tag->name;
    }
    public function getTag(string|int $tagID): ?object
    {
        $tag = get_tag((int)$tagID, $this->getTagTaxonomyName());
        if (!($tag instanceof WP_Term)) {
            return null;
        }
        return $tag;
    }
    public function getTagByName(string $tagName): ?object
    {
        $tag = get_term_by('name', $tagName, $this->getTagTaxonomyName());
        if (!($tag instanceof WP_Term)) {
            return null;
        }
        return $tag;
    }

    /**
     * @return array<string,int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTags(string|int|object $customPostObjectOrID, array $query = [], array $options = []): array
    {
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
        $query = $this->convertTagsQuery($query, $options);

        // Indicate to return the count
        $query['count'] = true;
        $query['fields'] = 'count';

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Execute query and return count
        /** @var int[] */
        $count = get_tags($query);
        // For some reason, the count is returned as an array of 1 element!
        if (is_array($count) && count($count) === 1 && is_numeric($count[0])) {
            return (int) $count[0];
        }
        // An error happened
        return -1;
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
    protected function convertCustomPostTaxonomyQuery(array $query, array $options = []): array
    {
        return $this->convertTagsQuery($query, $options);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function convertTagsQuery(array $query, array $options = []): array
    {
        $query = $this->convertTaxonomiesQuery($query, $options);

        // Convert the parameters
        $query['taxonomy'] = $this->getTagTaxonomyName();

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
    public function getTagURL(string|int|object $tagObjectOrID): string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        $url = get_term_link($tagObjectOrID, $this->getTagTaxonomyName());
        if ($url instanceof WP_Error) {
            return '';
        }
        return $url;
    }

    public function getTagURLPath(string|int|object $tagObjectOrID): string
    {
        $localURLPath = $this->getCMSHelperService()->getLocalURLPath($this->getTagURL($tagObjectOrID));
        if ($localURLPath === false) {
            return '';
        }
        return $localURLPath;
    }

    public function getTagSlug(string|int|object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        if ($tag === null) {
            return '';
        }
        /** @var WP_Term $tag */
        return $tag->slug;
    }
    public function getTagDescription(string|int|object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        if ($tag === null) {
            return '';
        }
        /** @var WP_Term $tag */
        return $tag->description;
    }
    public function getTagItemCount(string|int|object $tagObjectOrID): int
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        if ($tag === null) {
            return 0;
        }
        /** @var WP_Term $tag */
        return $tag->count;
    }
    public function getTagID(object $tag): string|int
    {
        /** @var WP_Term $tag */
        return $tag->term_id;
    }
}
