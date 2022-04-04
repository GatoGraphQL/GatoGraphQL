<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    use BasicServiceTrait;

    public const HOOK_QUERY = __CLASS__ . ':query';
    public final const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';

    protected function getTermObjectAndID(string | int | object $termObjectOrID): array
    {
        if (is_object($termObjectOrID)) {
            $termObject = $termObjectOrID;
            $termObjectID = $termObject->ID;
        } else {
            $termObjectID = $termObjectOrID;
            $termObject = \get_term($termObjectID);
        }
        return [
            $termObject,
            $termObjectID,
        ];
    }
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(string | int | object $termObjectOrID): string
    {
        list(
            $termObject,
            $termObjectID,
        ) = $this->getTermObjectAndID($termObjectOrID);
        return $termObject->taxonomy;
    }



    public function convertTaxonomiesQuery(array $query, array $options = []): array
    {
        if ($return_type = $options[QueryOptions::RETURN_TYPE] ?? null) {
            if ($return_type === ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type === ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        if (isset($query['hide-empty'])) {
            $query['hide_empty'] = $query['hide-empty'];
            unset($query['hide-empty']);
        } else {
            // By default: do not hide empty categories
            $query['hide_empty'] = false;
        }

        // Convert the parameters
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['exclude'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        if (isset($query['order'])) {
            $query['order'] = \esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
            $query['orderby'] = \esc_sql($this->getOrderByQueryArgValue($query['orderby']));
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            // To bring all results, get_categories/get_tags needs "number => 0" instead of -1
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
        if (isset($query['slug'])) {
            // Same param name, so do nothing
        }
        if (isset($query['parent-id'])) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    protected function getOrderByQueryArgValue(string $orderBy): string
    {
        $orderBy = match ($orderBy) {
            TaxonomyOrderBy::NAME => 'name',
            TaxonomyOrderBy::SLUG => 'slug',
            TaxonomyOrderBy::ID => 'term_id',
            TaxonomyOrderBy::PARENT => 'parent',
            TaxonomyOrderBy::COUNT => 'count',
            TaxonomyOrderBy::NONE => 'none',
            TaxonomyOrderBy::INCLUDE => 'include',
            TaxonomyOrderBy::SLUG__IN => 'slug__in',
            TaxonomyOrderBy::DESCRIPTION => 'description',
            default => $orderBy,
        };
        return App::applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
}
