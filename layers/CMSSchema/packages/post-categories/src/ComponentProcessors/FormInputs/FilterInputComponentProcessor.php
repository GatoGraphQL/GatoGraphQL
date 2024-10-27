<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategories\ComponentProcessors\FormInputs;

use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostCategories\TypeResolvers\EnumType\PostCategoryTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Taxonomies\FilterInputs\TaxonomyFilterInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY = 'filterinput-post-category-taxonomy';

    private ?TaxonomyFilterInput $taxonomyFilterInput = null;
    private ?PostCategoryTaxonomyEnumStringScalarTypeResolver $postCategoryTaxonomyEnumStringScalarTypeResolver = null;
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final protected function getTaxonomyFilterInput(): TaxonomyFilterInput
    {
        if ($this->taxonomyFilterInput === null) {
            /** @var TaxonomyFilterInput */
            $taxonomyFilterInput = $this->instanceManager->getInstance(TaxonomyFilterInput::class);
            $this->taxonomyFilterInput = $taxonomyFilterInput;
        }
        return $this->taxonomyFilterInput;
    }
    final protected function getPostCategoryTaxonomyEnumStringScalarTypeResolver(): PostCategoryTaxonomyEnumStringScalarTypeResolver
    {
        if ($this->postCategoryTaxonomyEnumStringScalarTypeResolver === null) {
            /** @var PostCategoryTaxonomyEnumStringScalarTypeResolver */
            $postCategoryTaxonomyEnumStringScalarTypeResolver = $this->instanceManager->getInstance(PostCategoryTaxonomyEnumStringScalarTypeResolver::class);
            $this->postCategoryTaxonomyEnumStringScalarTypeResolver = $postCategoryTaxonomyEnumStringScalarTypeResolver;
        }
        return $this->postCategoryTaxonomyEnumStringScalarTypeResolver;
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

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY => $this->getTaxonomyFilterInput(),
            default => null,
        };
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY => 'taxonomy',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY => $this->getPostCategoryTaxonomyEnumStringScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY => $this->__('Post category taxonomy', 'post-categories'),
            default => null,
        };
    }

    public function getFilterInputDefaultValue(Component $component): mixed
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_POST_CATEGORY_TAXONOMY => $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName(),
            default => null,
        };
    }
}
