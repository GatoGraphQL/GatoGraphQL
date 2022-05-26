<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors\CustomPostAuthorIDsFilterInputProcessor;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors\ExcludeCustomPostAuthorIDsFilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CustomPostAuthorIDsFilterInputProcessor $customPostAuthorIDsFilterInputProcessor = null;
    private ?ExcludeCustomPostAuthorIDsFilterInputProcessor $excludeCustomPostAuthorIDsFilterInputProcessor = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setCustomPostAuthorIDsFilterInputProcessor(CustomPostAuthorIDsFilterInputProcessor $customPostAuthorIDsFilterInputProcessor): void
    {
        $this->customPostAuthorIDsFilterInputProcessor = $customPostAuthorIDsFilterInputProcessor;
    }
    final protected function getCustomPostAuthorIDsFilterInputProcessor(): CustomPostAuthorIDsFilterInputProcessor
    {
        return $this->customPostAuthorIDsFilterInputProcessor ??= $this->instanceManager->getInstance(CustomPostAuthorIDsFilterInputProcessor::class);
    }
    final public function setExcludeCustomPostAuthorIDsFilterInputProcessor(ExcludeCustomPostAuthorIDsFilterInputProcessor $excludeCustomPostAuthorIDsFilterInputProcessor): void
    {
        $this->excludeCustomPostAuthorIDsFilterInputProcessor = $excludeCustomPostAuthorIDsFilterInputProcessor;
    }
    final protected function getExcludeCustomPostAuthorIDsFilterInputProcessor(): ExcludeCustomPostAuthorIDsFilterInputProcessor
    {
        return $this->excludeCustomPostAuthorIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeCustomPostAuthorIDsFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->getCustomPostAuthorIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->getExcludeCustomPostAuthorIDsFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => 'customPostAuthorIDs',
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => 'excludeCustomPostAuthorIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
