<?php

namespace PoPSchema\Tags\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Tags\ComponentConfiguration;
use PoPSchema\QueriedObject\TypeAPIs\TypeAPIUtils;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractFunctionAPI extends \PoPSchema\Taxonomies\WP\FunctionAPI implements \PoPSchema\Tags\FunctionAPI
{
    use APITypeDataResolverTrait;

    /**
     * Implement this function by the actual service
     *
     * @return string
     */
    abstract protected function getTaxonomyName(): string;
    /**
     * Implement this function by the actual service
     *
     * @return string
     */
    abstract protected function getTagBaseOption(): string;

    public function getTagName($tag_id)
    {
        $tag = get_term($tag_id, $this->getTaxonomyName());
        return $tag->name;
    }
    public function getTag($tag_id)
    {
        return get_tag($tag_id, $this->getTaxonomyName());
    }
    public function getTagByName($tag_name)
    {
        return get_term_by('name', $tag_name, $this->getTaxonomyName());
    }
    public function getCustomPostTags($post_id, array $query = [], array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);

        return \wp_get_post_terms($post_id, $this->getTaxonomyName(), $query);
    }
    public function getCustomPostTagCount($post_id, array $query = [], array $options = []): int
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
        $tags = \wp_get_post_terms($post_id, $this->getTaxonomyName(), $query);
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
        $tags = get_tags($query, ['taxonomy' => $this->getTaxonomyName()]);
        return count($tags);
    }
    public function getTags($query, array $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);
        return get_tags($query, ['taxonomy' => $this->getTaxonomyName()]);
    }

    public function convertTagsQuery($query, array $options = [])
    {
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type == ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

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
                $limit = TypeAPIUtils::getLimitOrMaxLimit(
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

        return HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:tags:query',
            $query,
            $options
        );
    }
    public function getTagLink($tag_id)
    {
        return get_term_link($tag_id, $this->getTaxonomyName());
    }
    public function getTagBase()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        return $cmsengineapi->getOption($this->getTagBaseOption());
    }

    public function setPostTags($post_id, array $tags, bool $append = false)
    {
        return wp_set_post_terms($post_id, $tags, $this->getTaxonomyName(), $append);
    }
}

