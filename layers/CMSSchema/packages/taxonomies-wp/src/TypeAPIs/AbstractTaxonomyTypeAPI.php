<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Taxonomies\Constants\TaxonomyOrderBy;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use WP_Error;
use WP_Post;

use WP_Taxonomy;
use WP_Term;
use function esc_sql;
use function get_term_by;
use function get_term_children;
use function get_term_link;
use function get_term;
use function get_terms;
use function wp_get_post_terms;

abstract class AbstractTaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    use BasicServiceTrait;
    
    public const HOOK_QUERY = __CLASS__ . ':query';
    public final const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';

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

    protected function getTaxonomyTermFromObjectOrID(string|int|WP_Term $taxonomyTermObjectOrID): ?WP_Term
    {
        if (is_object($taxonomyTermObjectOrID)) {
            /** @var WP_Term */
            return $taxonomyTermObjectOrID;
        }
        return $this->getTerm(
            $taxonomyTermObjectOrID,
            $this->getTaxonomyName(),
        );
    }

    protected function getTerm(string|int $termObjectID, string $taxonomy = ''): ?WP_Term
    {
        $term = get_term((int)$termObjectID, $taxonomy);
        if ($term instanceof WP_Error) {
            return null;
        }
        /** @var WP_Term */
        return $term;
    }
    
    abstract protected function getTaxonomyName(): string;

    /**
     * @return array<string,int>|object[]
     */
    protected function getCustomPostID(string|int|WP_Post $customPostObjectOrID): string|int
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            return $customPost->ID;
        }
        return $customPostObjectOrID;
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function getCustomPostTaxonomyTerms(string|int|WP_Post $customPostObjectOrID, array $query = [], array $options = []): array
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        $query = $this->convertTaxonomyTermsQuery($query, $options);
        $taxonomyTerms =  wp_get_post_terms(
            (int)$customPostID,
            $this->getTaxonomyName(),
            $query,
        );
        if ($taxonomyTerms instanceof WP_Error) {
            return [];
        }
        /** @var array<string|int>|object[] $taxonomyTerms */
        return $taxonomyTerms;
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function getCustomPostTaxonomyTermCount(
        string|int|WP_Post $customPostObjectOrID,
        array $query = [],
        array $options = []
    ): ?int {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);

        // There is no direct way to calculate the total
        // (Documentation mentions to pass arg "count" => `true` to `wp_get_post_categories`,
        // but it doesn't work)
        // So execute a normal `wp_get_post_categories` retrieving all the IDs, and count them
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertTaxonomyTermsQuery($query, $options);

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Resolve and count
        $taxonomyTerms = wp_get_post_terms(
            (int)$customPostID,
            $this->getTaxonomyName(),
            $query,
        );
        if ($taxonomyTerms instanceof WP_Error) {
            return null;
        }
        /** @var int[] $taxonomyTerms */
        return count($taxonomyTerms);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
        if ($return_type = $options[QueryOptions::RETURN_TYPE] ?? null) {
            if ($return_type === ReturnTypes::IDS) {
                $query['fields'] = 'ids';
            } elseif ($return_type === ReturnTypes::NAMES) {
                $query['fields'] = 'names';
            }
        }

        $query['taxonomy'] = $this->getTaxonomyName();

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
            $query['order'] = esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // This param can either be a string or an array. Eg:
            // $query['orderby'] => array('date' => 'DESC', 'title' => 'ASC');
            $query['orderby'] = esc_sql($this->getOrderByQueryArgValue($query['orderby']));
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
        if ($this->isHierarchical() && isset($query['parent-id'])) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    abstract protected function isHierarchical(): bool;

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

    /**
     * Indicates if the passed object is of type Taxonomy
     */
    protected function isInstanceOfTaxonomyType(object $object): bool
    {
        $isHierarchical = $this->isHierarchical();
        return ($object instanceof WP_Taxonomy)
            && (
                ($isHierarchical && $object->hierarchical)
                || (!$isHierarchical && !$object->hierarchical)
            );
    }

    protected function getTaxonomyTermName(string|int|WP_Term $taxonomyTermObjectOrID): ?string
    {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID);
        if ($taxonomyTerm === null) {
            return null;
        }
        return $taxonomyTerm->name;
    }

    protected function getTaxonomyTermByName(string $taxonomyTermName): ?WP_Term
    {
        $taxonomyTerm = get_term_by('name', $taxonomyTermName, $this->getTaxonomyName());
        if (!($taxonomyTerm instanceof WP_Term)) {
            return null;
        }
        return $taxonomyTerm;
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function getTaxonomyCount(array $query = [], array $options = []): int
    {
        $query = $this->convertTaxonomyTermsQuery($query, $options);

        // Indicate to return the count
        $query['count'] = true;
        $query['fields'] = 'count';

        // All results, no offset
        $query['number'] = 0;
        unset($query['offset']);

        // Execute query and return count
        /** @var int[] */
        $count = get_terms($query);

        // For some reason, the count is returned as an array of 1 element!
        if (is_array($count) && count($count) === 1 && is_numeric($count[0])) {
            return (int) $count[0];
        }
        // An error happened
        return 0;
    }

    protected function getTaxonomyTerm(string|int $taxonomyTermID): ?WP_Term
    {
        $taxonomyTerm = get_term(
            (int)$taxonomyTermID,
            $this->getTaxonomyName(),
        );
        if (!($taxonomyTerm instanceof WP_Term)) {
            return null;
        }
        return $taxonomyTerm;
    }

    protected function getTaxonomyTermURL(string|int|WP_Term $taxonomyTermObjectOrID): ?string
    {
        $taxonomyTermLink = get_term_link(
            $taxonomyTermObjectOrID,
            $this->getTaxonomyName(),
        );
        if ($taxonomyTermLink instanceof WP_Error) {
            return null;
        }
        return $taxonomyTermLink;
    }

    protected function getTaxonomyTermURLPath(string|int|WP_Term $taxonomyTermObjectOrID): ?string
    {
        $url = $this->getTaxonomyTermURL($taxonomyTermObjectOrID);
        if ($url === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($url);
    }

    protected function getTaxonomyTermSlug(string|int|WP_Term $taxonomyTermObjectOrID): ?string
    {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID);
        if ($taxonomyTerm === null) {
            return null;
        }
        /** @var WP_Term $taxonomyTerm */
        return $taxonomyTerm->slug;
    }

    protected function getTaxonomyTermDescription(string|int|WP_Term $taxonomyTermObjectOrID): ?string
    {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID);
        if ($taxonomyTerm === null) {
            return null;
        }
        /** @var WP_Term $taxonomyTerm */
        return $taxonomyTerm->description;
    }

    protected function getTaxonomyTermItemCount(string|int|WP_Term $taxonomyTermObjectOrID): ?int
    {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID);
        if ($taxonomyTerm === null) {
            return null;
        }
        /** @var WP_Term $taxonomyTerm */
        return $taxonomyTerm->count;
    }
    
    protected function getTaxonomyTermID(WP_Term $taxonomyTerm): string|int
    {
        return $taxonomyTerm->term_id;
    }

    protected function getTaxonomyTermParentID(string|int|WP_Term $taxonomyTermObjectOrID): string|int|null
    {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID($taxonomyTermObjectOrID);
        if ($taxonomyTerm === null) {
            return null;
        }
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $taxonomyTerm->parent) {
            return $parent;
        }
        return null;
    }

    /**
     * @return array<string|int>|null
     */
    protected function getTaxonomyTermChildIDs(string|int|WP_Term $taxonomyTermObjectOrID): ?array
    {
        $taxonomyTermID = is_object($taxonomyTermObjectOrID) ? $this->getTaxonomyTermID($taxonomyTermObjectOrID) : $taxonomyTermObjectOrID;
        $childrenIDs = get_term_children((int)$taxonomyTermID, $this->getTaxonomyName());
        if ($childrenIDs instanceof WP_Error) {
            return null;
        }
        return $childrenIDs;
    }
}
