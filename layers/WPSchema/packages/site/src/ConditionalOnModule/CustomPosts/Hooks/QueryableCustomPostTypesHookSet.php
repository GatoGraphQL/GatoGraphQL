<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\ConditionalOnModule\CustomPosts\Hooks;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPWPSchema\Site\Constants\HookNames;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

use function is_array;

/**
 * `_urlToEntity` resolves the entries of the custom post types that the schema lets be
 * queried only.
 */
class QueryableCustomPostTypesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::URL_TO_ENTITY_QUERYABLE_CUSTOM_POST_TYPES,
            $this->addQueryableCustomPosts(...)
        );
    }

    /**
     * @param string[]|null $customPostTypes
     * @return string[]
     */
    public function addQueryableCustomPosts(?array $customPostTypes): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            ...(is_array($customPostTypes) ? $customPostTypes : []),
            ...$moduleConfiguration->getQueryableCustomPostTypes(),
        ];
    }
}
