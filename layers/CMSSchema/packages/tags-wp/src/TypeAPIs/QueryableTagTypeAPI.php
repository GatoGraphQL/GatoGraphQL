<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;
use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoP\ComponentModel\App;

class QueryableTagTypeAPI extends AbstractTagTypeAPI implements QueryableTagTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * There will be more than 1 taxonomies, but this value
     * will get replaced in the query below
     */
    protected function getTagTaxonomyName(): string
    {
        return '';
    }

    /**
     * @return string[]
     */
    protected function getTagTaxonomyNames(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getQueryableTagTaxonomies();
    }
}
