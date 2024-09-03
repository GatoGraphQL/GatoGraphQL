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

    /**
     * @return string[]
     */
    protected function getCategoryTaxonomyNames(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getQueryableCategoryTaxonomies();
    }
}
