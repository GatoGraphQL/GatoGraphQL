<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoriesWP\TypeAPIs;

use PoPCMSSchema\CategoriesWP\TypeAPIs\AbstractCategoryTypeAPI;
use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoP\ComponentModel\App;

class QueryableCategoryTypeAPI extends AbstractCategoryTypeAPI implements QueryableCategoryTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    protected function getCategoryBaseOption(): string
    {
        return '';
    }

    /**
     * There will be more than 1 taxonomies, but this value
     * will get replaced in the query below
     */
    public function getCategoryTaxonomyName(): string
    {
        return '';
    }

    /**
     * Replace the single taxonomy with the list of them.
     *
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
        $query = parent::convertTaxonomyTermsQuery($query, $options);

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableCategoryTaxonomies();
        
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
}
