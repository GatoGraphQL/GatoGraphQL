<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\ConditionalOnModule\Categories\Hooks;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPWPSchema\Site\Constants\HookNames;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

use function is_array;

/**
 * `_urlToEntity` resolves the entries of the category taxonomies that the schema lets be
 * queried only.
 */
class QueryableCategoryTaxonomiesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::URL_TO_ENTITY_QUERYABLE_TAXONOMIES,
            $this->addQueryableCategories(...)
        );
    }

    /**
     * @param string[]|null $taxonomies
     * @return string[]
     */
    public function addQueryableCategories(?array $taxonomies): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            ...(is_array($taxonomies) ? $taxonomies : []),
            ...$moduleConfiguration->getQueryableCategoryTaxonomies(),
        ];
    }
}
