<?php

namespace PoPSchema\Media\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Media\ComponentConfiguration;
use PoPSchema\QueriedObject\TypeAPIs\TypeAPIUtils;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class FunctionAPI extends \PoPSchema\Media\FunctionAPI_Base
{
    public function getMediaObject($media_id)
    {
        return get_post($media_id);
    }
    public function getMediaDescription($media_id)
    {
        $media = get_post($media_id);
        return $media->post_content;
    }
    public function getMediaSrc($image_id, $size = null)
    {
        return wp_get_attachment_image_src($image_id, $size);
    }
    public function getMediaMimeType($media_id)
    {
        return get_post_mime_type($media_id);
    }
    public function getMediaAuthorId($media_id): int
    {
        $media = get_post($media_id);
        return $media->post_author;
    }
    public function getMediaElements(array $query, array $options = []): array
    {
        // Convert the parameters
        $query = $this->convertMediaQuery($query, $options);
        return get_posts($query);
    }
    protected function convertMediaQuery($query, array $options = [])
    {
        if ($return_type = $options['return-type'] ?? null) {
            if ($return_type == ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            }
        }

        if (isset($query['include'])) {
            // Transform from array to string
            $query['include'] = implode(',', $query['include']);
        }
        $query['post_type'] = 'attachment';
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
                    ComponentConfiguration::getMediaListMaxLimit()
                );
            }

            // Assign the limit as the required attribute
            $query['posts_per_page'] = $limit;
            unset($query['limit']);
        }
        if (isset($query['order'])) {
            // Same param name, so do nothing
        }
        if (isset($query['orderby'])) {
            // Same param name, so do nothing
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
        }
        if (isset($query['search'])) {
            $query['is_search'] = true;
            $query['s'] = $query['search'];
            unset($query['search']);
        }
        // Filtering by date: Instead of operating on the query, it does it through filter 'posts_where'
        if (isset($query['date-from'])) {
            $query['date_query'][] = [
                'after' => $query['date-from'],
                'inclusive' => false,
            ];
            unset($query['date-from']);
        }
        if (isset($query['date-from-inclusive'])) {
            $query['date_query'][] = [
                'after' => $query['date-from-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-from-inclusive']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }
        if (isset($query['date-to-inclusive'])) {
            $query['date_query'][] = [
                'before' => $query['date-to-inclusive'],
                'inclusive' => true,
            ];
            unset($query['date-to-inclusive']);
        }

        $query = HooksAPIFacade::getInstance()->applyFilters(
            'CMSAPI:media:query',
            $query,
            $options
        );
        return $query;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
