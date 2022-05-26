<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ExcludeParentIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDsFilterInputProcessor;

abstract class AbstractPagesFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements PagesFilterInputObjectTypeResolverInterface
{
    private ?ParentIDFilterInputProcessor $parentIDFilterInputProcessor = null;
    private ?ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor = null;
    private ?ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor = null;

    final public function setParentIDFilterInputProcessor(ParentIDFilterInputProcessor $parentIDFilterInputProcessor): void
    {
        $this->parentIDFilterInputProcessor = $parentIDFilterInputProcessor;
    }
    final protected function getParentIDFilterInputProcessor(): ParentIDFilterInputProcessor
    {
        return $this->parentIDFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDFilterInputProcessor::class);
    }
    final public function setParentIDsFilterInputProcessor(ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor): void
    {
        $this->parentIDsFilterInputProcessor = $parentIDsFilterInputProcessor;
    }
    final protected function getParentIDsFilterInputProcessor(): ParentIDsFilterInputProcessor
    {
        return $this->parentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDsFilterInputProcessor::class);
    }
    final public function setExcludeParentIDsFilterInputProcessor(ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor): void
    {
        $this->excludeParentIDsFilterInputProcessor = $excludeParentIDsFilterInputProcessor;
    }
    final protected function getExcludeParentIDsFilterInputProcessor(): ExcludeParentIDsFilterInputProcessor
    {
        return $this->excludeParentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeParentIDsFilterInputProcessor::class);
    }

    abstract protected function addParentInputFields(): bool;

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            $this->addParentInputFields() ? [
                'parentID' => $this->getIDScalarTypeResolver(),
                'parentIDs' => $this->getIDScalarTypeResolver(),
                'excludeParentIDs' => $this->getIDScalarTypeResolver(),
            ] : [],
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'parentID' => $this->__('Filter pages with the given parent IDs. \'0\' means \'no parent\'', 'pages'),
            'parentIDs' => $this->__('Filter pages with the given parent ID. \'0\' means \'no parent\'', 'pages'),
            'excludeParentIDs' => $this->__('Exclude pages with the given parent IDs', 'pages'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            'parentIDs',
            'excludeParentIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName)
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'parentID' => $this->getParentIDFilterInputProcessor(),
            'parentIDs' => $this->getParentIDsFilterInputProcessor(),
            'excludeParentIDs' => $this->getExcludeParentIDsFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
