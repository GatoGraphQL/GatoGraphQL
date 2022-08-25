<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use WP_Error;
use WP_Post;
use WP_Taxonomy;
use WP_Term;

use function get_tag;
use function get_term_by;
use function wp_get_post_terms;
use function get_tags;
use function get_term_link;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends TaxonomyTypeAPI implements TagTypeAPIInterface
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

    abstract protected function getTagTaxonomyName(): string;


    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical;
    }

    protected function getTagFromObjectOrID(string|int|object $tagObjectOrID): ?object
    {
        return is_object($tagObjectOrID) ?
            $tagObjectOrID
            : $this->getTerm($tagObjectOrID, $this->getTagTaxonomyName());
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
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
        }
        $query = $this->convertTagsQuery($query, $options);
        $tags = wp_get_post_terms((int)$customPostID, $this->getTagTaxonomyName(), $query);
        if ($tags instanceof WP_Error) {
            return [];
        }
        /** @var object[] $tags */
        return $tags;
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostTagCount(string|int|object $customPostObjectOrID, array $query = [], array $options = []): int
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
        }
        // There is no direct way to calculate the total
        // (Documentation mentions to pass arg "count" => `true` to `wp_get_post_tags`,
        // but it doesn't work)
        // So execute a normal `wp_get_post_tags` retrieving all the IDs, and count them
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertTagsQuery($query, $options);

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Resolve and count
        $tags = wp_get_post_terms((int)$customPostID, $this->getTagTaxonomyName(), $query);
        if ($tags instanceof WP_Error) {
            return 0;
        }
        /** @var object[] $tags */
        return count($tags);
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
