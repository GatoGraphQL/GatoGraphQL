<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\ConditionalOnModule\Tags\Hooks;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPWPSchema\Site\Constants\HookNames;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

use function is_array;

/**
 * `_urlToEntity` resolves the entries of the tag taxonomies that the schema lets be
 * queried only.
 */
class QueryableTagTaxonomiesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::URL_TO_ENTITY_QUERYABLE_TAXONOMIES,
            $this->addQueryableTags(...)
        );
    }

    /**
     * @param string[]|null $taxonomies
     * @return string[]
     */
    public function addQueryableTags(?array $taxonomies): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            ...(is_array($taxonomies) ? $taxonomies : []),
            ...$moduleConfiguration->getQueryableTagTaxonomies(),
        ];
    }
}
