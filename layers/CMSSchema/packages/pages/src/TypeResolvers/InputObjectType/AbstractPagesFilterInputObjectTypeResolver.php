<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput;

abstract class AbstractPagesFilterInputObjectTypeResolver extends AbstractCustomPostsFilterInputObjectTypeResolver implements PagesFilterInputObjectTypeResolverInterface
{
    private ?ParentIDFilterInput $parentIDFilterInput = null;
    private ?ParentIDsFilterInput $parentIDsFilterInput = null;
    private ?ExcludeParentIDsFilterInput $excludeParentIDsFilterInput = null;

    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        if ($this->parentIDFilterInput === null) {
            /** @var ParentIDFilterInput */
            $parentIDFilterInput = $this->instanceManager->getInstance(ParentIDFilterInput::class);
            $this->parentIDFilterInput = $parentIDFilterInput;
        }
        return $this->parentIDFilterInput;
    }
    final protected function getParentIDsFilterInput(): ParentIDsFilterInput
    {
        if ($this->parentIDsFilterInput === null) {
            /** @var ParentIDsFilterInput */
            $parentIDsFilterInput = $this->instanceManager->getInstance(ParentIDsFilterInput::class);
            $this->parentIDsFilterInput = $parentIDsFilterInput;
        }
        return $this->parentIDsFilterInput;
    }
    final protected function getExcludeParentIDsFilterInput(): ExcludeParentIDsFilterInput
    {
        if ($this->excludeParentIDsFilterInput === null) {
            /** @var ExcludeParentIDsFilterInput */
            $excludeParentIDsFilterInput = $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
            $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
        }
        return $this->excludeParentIDsFilterInput;
    }

    abstract protected function addParentInputFields(): bool;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'parentID' => $this->getParentIDFilterInput(),
            'parentIDs' => $this->getParentIDsFilterInput(),
            'excludeParentIDs' => $this->getExcludeParentIDsFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
