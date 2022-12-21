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

    public function getTag(string|int $tagID): ?object
    {
        $tag = parent::getTag($tagID);
        if ($tag === null) {
            return null;
        }
        /** @var WP_Term $tag */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!in_array($tag->taxonomy, $moduleConfiguration->getQueryableTagTaxonomies())) {
            return null;
        }
        return $tag;
    }

    /**
     * Indicates if the passed object is of type Tag
     */
    public function isInstanceOfTagType(object $object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return in_array($object->taxonomy, $moduleConfiguration->getQueryableTagTaxonomies());
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
        $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();
        
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
}
