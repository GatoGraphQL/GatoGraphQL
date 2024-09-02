<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\TypeResolvers\EnumType;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoP\ComponentModel\App;

class PostCategoryTaxonomyEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'PostCategoryTaxonomyEnumString';
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Post category taxonomies (available for querying via the API), with possible values: `"%s"`.', 'categories'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * Return all the category taxonomies registered for the "post"
     * custom post type, that have also been selected as "queryable"
     * in the plugin settings.
     *
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $queryableCategoryTaxonomies = $moduleConfiguration->getQueryableCategoryTaxonomies();

        return array_values(array_intersect(
            $this->getPostCategoryTypeAPI()->getRegisteredPostCategoryTaxonomyNames(),
            $queryableCategoryTaxonomies
        ));
    }
}
