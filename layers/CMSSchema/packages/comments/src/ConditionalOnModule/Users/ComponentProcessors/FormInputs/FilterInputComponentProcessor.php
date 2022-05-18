<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public final const MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => 'customPostAuthorIDs',
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => 'excludeCustomPostAuthorIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::MODULE_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS => $this->__('Get results from the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
