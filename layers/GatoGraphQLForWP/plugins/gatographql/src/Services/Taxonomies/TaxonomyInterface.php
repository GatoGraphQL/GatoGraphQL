<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

interface TaxonomyInterface
{
    public function getTaxonomy(): string;

    public function getTaxonomyName(bool $titleCase = true): string;

    /**
     * Taxonomy plural name
     *
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames(bool $titleCase = true): string;

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array;

    public function isHierarchical(): bool;

    /**
     * Show in menu
     */
    public function showInMenu(): ?string;
}
