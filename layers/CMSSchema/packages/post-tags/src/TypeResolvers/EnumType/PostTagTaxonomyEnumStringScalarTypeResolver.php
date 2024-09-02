<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\TypeResolvers\EnumType;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

class PostTagTaxonomyEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        if ($this->postTagTypeAPI === null) {
            /** @var PostTagTypeAPIInterface */
            $postTagTypeAPI = $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
            $this->postTagTypeAPI = $postTagTypeAPI;
        }
        return $this->postTagTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'PostTagTaxonomyEnumString';
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Post tag taxonomies (available for querying via the API), with possible values: `"%s"`.', 'tags'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * Return all the tag taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableTagTaxonomies = $moduleConfiguration->getQueryableTagTaxonomies();

        return array_values(array_intersect(
            $this->getPostTagTypeAPI()->getRegisteredPostTagTaxonomyNames(),
            $queryableTagTaxonomies
        ));
    }
}
