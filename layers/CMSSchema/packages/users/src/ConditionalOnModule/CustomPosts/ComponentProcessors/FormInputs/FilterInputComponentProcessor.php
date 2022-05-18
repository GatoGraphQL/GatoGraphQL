<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\FilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public final const MODULE_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public final const MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_IDS],
            [self::class, self::MODULE_FILTERINPUT_AUTHOR_SLUG],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_IDS],
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_AUTHOR_SLUG],
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    public function getName(array $componentVariation): string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => 'authorIDs',
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => 'authorSlug',
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => 'excludeAuthorIDs',
            default => parent::getName($componentVariation),
        };
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::MODULE_FILTERINPUT_AUTHOR_SLUG => $this->__('Get results from the authors with given slug', 'pop-users'),
            self::MODULE_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
