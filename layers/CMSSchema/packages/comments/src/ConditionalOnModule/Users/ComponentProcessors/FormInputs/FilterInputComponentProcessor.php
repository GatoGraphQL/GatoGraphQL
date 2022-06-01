<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput = null;
    private ?ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setCustomPostAuthorIDsFilterInput(CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput): void
    {
        $this->customPostAuthorIDsFilterInput = $customPostAuthorIDsFilterInput;
    }
    final protected function getCustomPostAuthorIDsFilterInput(): CustomPostAuthorIDsFilterInput
    {
        return $this->customPostAuthorIDsFilterInput ??= $this->instanceManager->getInstance(CustomPostAuthorIDsFilterInput::class);
    }
    final public function setExcludeCustomPostAuthorIDsFilterInput(ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput): void
    {
        $this->excludeCustomPostAuthorIDsFilterInput = $excludeCustomPostAuthorIDsFilterInput;
    }
    final protected function getExcludeCustomPostAuthorIDsFilterInput(): ExcludeCustomPostAuthorIDsFilterInput
    {
        return $this->excludeCustomPostAuthorIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeCustomPostAuthorIDsFilterInput::class);
    }

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->getCustomPostAuthorIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->getExcludeCustomPostAuthorIDsFilterInput(),
            default => null,
        };
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => 'customPostAuthorIDs',
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => 'excludeCustomPostAuthorIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
