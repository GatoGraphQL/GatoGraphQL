<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\TypeAPIs;

use PoP\ComponentModel\TypeDataResolvers\InjectedFilterDataloadingModuleTypeDataResolverTrait;
use PoP\Hooks\HooksAPIInterface;
use PoPSchema\QueriedObject\Helpers\QueriedObjectHelperServiceInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI;
use WP_Taxonomy;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends TaxonomyTypeAPI implements TagTypeAPIInterface
{
    use InjectedFilterDataloadingModuleTypeDataResolverTrait;

    function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected QueriedObjectHelperServiceInterface $queriedObjectHelperService,
    ) {        
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
        $options['return-type'] = ReturnTypes::IDS;
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
        // There is no direct way to calculate the total
        // (Documentation mentions to pass arg "count" => `true` to `get_tags`,
        // but it doesn't work)
        // So execute a normal `get_tags` retrieving all the IDs, and count them
        $options['return-type'] = ReturnTypes::IDS;
        $query = $this->convertTagsQuery($query, $options);

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Resolve and count
        $tags = get_tags($query);
        return count($tags);
    }
    public function getTags(array $query, array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);
        return get_tags($query);
    }

    public function convertTagsQuery(array $query, array $options = []): array
    {
        $query['taxonomy'] = $this->getTagTaxonomyName();

        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type == ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        } else {
            // By default: do not hide empty tags
            $query['hide_empty'] = false;
        }

        // Convert the parameters
        if (isset($query['include'])) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            // Maybe restrict the limit, if higher than the max limit
            // Allow to not limit by max when querying from within the application
            $limit = (int) $query['limit'];
            if (!isset($options['skip-max-limit']) || !$options['skip-max-limit']) {
                $limit = $this->queriedObjectHelperService->getLimitOrMaxLimit(
                    $limit,
                    ComponentConfiguration::getTagListMaxLimit()
                );
            }

            // Assign the limit as the required attribute
            // To bring all results, get_tags needs "number => 0" instead of -1
            $query['number'] = ($limit == -1) ? 0 : $limit;
            unset($query['limit']);
        }
        if (isset($query['search'])) {
            // Same param name, so do nothing
        }
        if (isset($query['slugs'])) {
            $query['slug'] = $query['slugs'];
            unset($query['slugs']);
        }

        return $this->hooksAPI->applyFilters(
            'CMSAPI:taxonomies:query',
            $this->hooksAPI->applyFilters(
                'CMSAPI:tags:query',
                $query,
                $options
            ),
            $query,
            $options
        );
    }
    public function getTagURL(string | int $tagID): string
    {
        return get_term_link($tagID, $this->getTagTaxonomyName());
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
