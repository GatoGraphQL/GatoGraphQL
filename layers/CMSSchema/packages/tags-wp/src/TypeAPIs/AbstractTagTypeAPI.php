<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use WP_Taxonomy;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends TaxonomyTypeAPI implements TagTypeAPIInterface
{
    public final const HOOK_QUERY = __CLASS__ . ':query';

    private ?CMSHelperServiceInterface $cmsHelperService = null;

    final public function setCMSHelperService(CMSHelperServiceInterface $cmsHelperService): void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    final protected function getCMSHelperService(): CMSHelperServiceInterface
    {
        return $this->cmsHelperService ??= $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }

    abstract protected function getTagTaxonomyName(): string;


    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        return ($object instanceof WP_Taxonomy) && $object->hierarchical == false;
    }

    protected function getTagFromObjectOrID(string | int | object $tagObjectOrID): object
    {
        return is_object($tagObjectOrID) ?
            $tagObjectOrID
            : \get_term($tagObjectOrID, $this->getTagTaxonomyName());
    }
    public function getTagName(string | int | object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        return $tag->name;
    }
    public function getTag(string | int $tagID): object
    {
        return get_tag($tagID, $this->getTagTaxonomyName());
    }
    public function getTagByName(string $tagName): object
    {
        return get_term_by('name', $tagName, $this->getTagTaxonomyName());
    }
    public function getCustomPostTags(string | int $customPostID, array $query = [], array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);

        return \wp_get_post_terms($customPostID, $this->getTagTaxonomyName(), $query);
    }
    public function getCustomPostTagCount(string | int $customPostID, array $query = [], array $options = []): int
    {
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
        $tags = \wp_get_post_terms($customPostID, $this->getTagTaxonomyName(), $query);
        return count($tags);
    }
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
        $count = \get_tags($query);
        // For some reason, the count is returned as an array of 1 element!
        if (is_array($count) && count($count) === 1 && is_numeric($count[0])) {
            return (int) $count[0];
        }
        // An error happened
        return -1;
    }
    public function getTags(array $query, array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);
        return get_tags($query);
    }

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
    public function getTagURL(string | int | object $tagObjectOrID): string
    {
        return get_term_link($tagObjectOrID, $this->getTagTaxonomyName());
    }

    public function getTagURLPath(string | int | object $tagObjectOrID): string
    {
        /** @var string */
        return $this->getCMSHelperService()->getLocalURLPath($this->getTagURL($tagObjectOrID));
    }

    public function getTagSlug(string | int | object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        return $tag->slug;
    }
    public function getTagDescription(string | int | object $tagObjectOrID): string
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        return $tag->description;
    }
    public function getTagItemCount(string | int | object $tagObjectOrID): int
    {
        $tag = $this->getTagFromObjectOrID($tagObjectOrID);
        return $tag->count;
    }
    public function getTagID(object $tag): string | int
    {
        return $tag->term_id;
    }
}
