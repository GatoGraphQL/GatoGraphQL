<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\AuthorIDsFilterInputProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\AuthorSlugFilterInputProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\ExcludeAuthorIDsFilterInputProcessor;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public final const COMPONENT_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?AuthorIDsFilterInputProcessor $authorIDsFilterInputProcessor = null;
    private ?AuthorSlugFilterInputProcessor $authorSlugFilterInputProcessor = null;
    private ?ExcludeAuthorIDsFilterInputProcessor $excludeAuthorIDsFilterInputProcessor = null;

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
    final public function setAuthorIDsFilterInputProcessor(AuthorIDsFilterInputProcessor $authorIDsFilterInputProcessor): void
    {
        $this->authorIDsFilterInputProcessor = $authorIDsFilterInputProcessor;
    }
    final protected function getAuthorIDsFilterInputProcessor(): AuthorIDsFilterInputProcessor
    {
        return $this->authorIDsFilterInputProcessor ??= $this->instanceManager->getInstance(AuthorIDsFilterInputProcessor::class);
    }
    final public function setAuthorSlugFilterInputProcessor(AuthorSlugFilterInputProcessor $authorSlugFilterInputProcessor): void
    {
        $this->authorSlugFilterInputProcessor = $authorSlugFilterInputProcessor;
    }
    final protected function getAuthorSlugFilterInputProcessor(): AuthorSlugFilterInputProcessor
    {
        return $this->authorSlugFilterInputProcessor ??= $this->instanceManager->getInstance(AuthorSlugFilterInputProcessor::class);
    }
    final public function setExcludeAuthorIDsFilterInputProcessor(ExcludeAuthorIDsFilterInputProcessor $excludeAuthorIDsFilterInputProcessor): void
    {
        $this->excludeAuthorIDsFilterInputProcessor = $excludeAuthorIDsFilterInputProcessor;
    }
    final protected function getExcludeAuthorIDsFilterInputProcessor(): ExcludeAuthorIDsFilterInputProcessor
    {
        return $this->excludeAuthorIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeAuthorIDsFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_AUTHOR_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_AUTHOR_SLUG],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS],
        );
    }

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->getAuthorIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->getAuthorSlugFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getExcludeAuthorIDsFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getName(array $component): string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => 'authorIDs',
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => 'authorSlug',
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => 'excludeAuthorIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->__('Get results from the authors with given slug', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
