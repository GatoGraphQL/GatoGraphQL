<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput;

class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public final const COMPONENT_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?AuthorIDsFilterInput $authorIDsFilterInput = null;
    private ?AuthorSlugFilterInput $authorSlugFilterInput = null;
    private ?ExcludeAuthorIDsFilterInput $excludeAuthorIDsFilterInput = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getAuthorIDsFilterInput(): AuthorIDsFilterInput
    {
        if ($this->authorIDsFilterInput === null) {
            /** @var AuthorIDsFilterInput */
            $authorIDsFilterInput = $this->instanceManager->getInstance(AuthorIDsFilterInput::class);
            $this->authorIDsFilterInput = $authorIDsFilterInput;
        }
        return $this->authorIDsFilterInput;
    }
    final protected function getAuthorSlugFilterInput(): AuthorSlugFilterInput
    {
        if ($this->authorSlugFilterInput === null) {
            /** @var AuthorSlugFilterInput */
            $authorSlugFilterInput = $this->instanceManager->getInstance(AuthorSlugFilterInput::class);
            $this->authorSlugFilterInput = $authorSlugFilterInput;
        }
        return $this->authorSlugFilterInput;
    }
    final protected function getExcludeAuthorIDsFilterInput(): ExcludeAuthorIDsFilterInput
    {
        if ($this->excludeAuthorIDsFilterInput === null) {
            /** @var ExcludeAuthorIDsFilterInput */
            $excludeAuthorIDsFilterInput = $this->instanceManager->getInstance(ExcludeAuthorIDsFilterInput::class);
            $this->excludeAuthorIDsFilterInput = $excludeAuthorIDsFilterInput;
        }
        return $this->excludeAuthorIDsFilterInput;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG,
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->getAuthorIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->getAuthorSlugFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getExcludeAuthorIDsFilterInput(),
            default => null,
        };
    }

    public function getName(Component $component): string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => 'authorIDs',
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => 'authorSlug',
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => 'excludeAuthorIDs',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->getIDScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(Component $component): ?string
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_AUTHOR_IDS => $this->__('Get results from the authors with given IDs', 'pop-users'),
            self::COMPONENT_FILTERINPUT_AUTHOR_SLUG => $this->__('Get results from the authors with given slug', 'pop-users'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => null,
        };
    }
}
