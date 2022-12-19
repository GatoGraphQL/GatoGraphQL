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
    protected function getTagBaseOption(): string
    {
        return '';
    }

    /**
     * There will be more than 1 taxonomies, but this value
     * will get replaced in the query below
     */
    public function getTagTaxonomyName(): string
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
    public function convertTagsQuery(array $query, array $options = []): array
    {
        $query = parent::convertTagsQuery($query, $options);

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();
        return $query;
    }
}
