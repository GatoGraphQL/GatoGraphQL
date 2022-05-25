<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors\CustomPostAuthorIDsFilterInputProcessor;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputProcessors\ExcludeCustomPostAuthorIDsFilterInputProcessor;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\AuthorIDsFilterInputProcessor;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputProcessors\ExcludeAuthorIDsFilterInputProcessor;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?CustomPostAuthorIDsFilterInputProcessor $customPostAuthorIDsFilterInputProcessor = null;
    private ?ExcludeCustomPostAuthorIDsFilterInputProcessor $excludeCustomPostAuthorIDsFilterInputProcessor = null;
    private ?AuthorIDsFilterInputProcessor $authorIDsFilterInputProcessor = null;
    private ?ExcludeAuthorIDsFilterInputProcessor $excludeAuthorIDsFilterInputProcessor = null;

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
    final public function setAuthorIDsFilterInputProcessor(AuthorIDsFilterInputProcessor $authorIDsFilterInputProcessor): void
    {
        $this->authorIDsFilterInputProcessor = $authorIDsFilterInputProcessor;
    }
    final protected function getAuthorIDsFilterInputProcessor(): AuthorIDsFilterInputProcessor
    {
        return $this->authorIDsFilterInputProcessor ??= $this->instanceManager->getInstance(AuthorIDsFilterInputProcessor::class);
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
     * @param array<string, InputTypeResolverInterface> $inputFieldNameTypeResolvers
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'authorIDs' => $this->getIDScalarTypeResolver(),
                'excludeAuthorIDs' => $this->getIDScalarTypeResolver(),
                'customPostAuthorIDs' => $this->getIDScalarTypeResolver(),
                'excludeCustomPostAuthorIDs' => $this->getIDScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->__('Filter comments from the authors with given IDs', 'comments'),
            'excludeAuthorIDs' => $this->__('Exclude comments from authors with given IDs', 'comments'),
            'customPostAuthorIDs' => $this->__('Filter comments added to custom posts from the authors with given IDs', 'comments'),
            'excludeCustomPostAuthorIDs' => $this->__('Exclude comments added to custom posts from authors with given IDs', 'comments'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'authorIDs',
            'excludeAuthorIDs',
            'customPostAuthorIDs',
            'excludeCustomPostAuthorIDs'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => $inputFieldTypeModifiers,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputProcessorInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputProcessorInterface {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'authorIDs' => $this->getAuthorIDsFilterInputProcessor(),
            'excludeAuthorIDs' => $this->getExcludeAuthorIDsFilterInputProcessor(),
            'customPostAuthorIDs' => $this->getCustomPostAuthorIDsFilterInputProcessor(),
            'excludeCustomPostAuthorIDs' => $this->getExcludeCustomPostAuthorIDsFilterInputProcessor(),
            default => $inputFieldFilterInput,
        };
    }
}
