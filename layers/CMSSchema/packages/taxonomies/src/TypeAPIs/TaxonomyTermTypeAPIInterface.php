<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TaxonomyTermTypeAPIInterface
{
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(object $taxonomyTerm): string;
    public function taxonomyTermExists(int|string $id, string $taxonomy = ''): bool;
    public function getTaxonomyTermID(int|string $taxonomyTermIDOrSlug, string $taxonomy = ''): string|int|null;
    public function getTaxonomyTermTaxonomy(int|string $taxonomyTermID): string|null;
    public function getTaxonomyTerm(int|string $taxonomyTermID, string $taxonomy = ''): object|null;
    public function canUserEditTaxonomy(string|int $userID, string $taxonomyName): bool;
    public function canUserAssignTermsToTaxonomy(string|int $userID, string $taxonomyName): bool;
    public function canUserDeleteTaxonomyTerm(string|int $userID, string|int $taxonomyTermID): bool;
    public function getTaxonomy(string $taxonomyName): object|null;
    public function taxonomyExists(string $taxonomyName): bool;
}
