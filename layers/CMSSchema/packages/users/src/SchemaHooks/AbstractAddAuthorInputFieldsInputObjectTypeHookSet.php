<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\SchemaHooks;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\AuthorIDsFilterInputProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\AuthorSlugFilterInputProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\ExcludeAuthorIDsFilterInputProcessor;

abstract class AbstractAddAuthorInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    use AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait;

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

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(...),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            $this->getInputFieldTypeModifiers(...),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            $this->getInputFieldFilterInput(...),
            10,
            3
        );
    }

    /**
     * Indicate if to add the fields added by the SchemaHookSet
     */
    abstract protected function addAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool;

    /**
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            $this->getAuthorInputFieldNameTypeResolvers(),
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->__('Get results from the authors with given IDs', 'pop-users'),
            'authorSlug' => $this->__('Get results from the authors with given slug', 'pop-users'),
            'excludeAuthorIDs' => $this->__('Get results excluding the ones from authors with given IDs', 'pop-users'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'authorIDs',
            'excludeAuthorIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => $inputFieldTypeModifiers,
        };
    }

    public function getInputFieldFilterInput(
        ?array $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputProcessorInterface {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->getAuthorIDsFilterInputProcessor(),
            'authorSlug' => $this->getAuthorSlugFilterInputProcessor(),
            'excludeAuthorIDs' => $this->getExcludeAuthorIDsFilterInputProcessor(),
            default => $inputFieldFilterInput,
        };
    }
}
