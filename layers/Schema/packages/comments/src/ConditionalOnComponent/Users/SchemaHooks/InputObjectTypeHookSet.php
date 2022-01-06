<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\SchemaHooks;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\Comments\ConditionalOnComponent\Users\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\FilterInputProcessors\FilterInputProcessor as UsersCustomPostsFilterInputProcessor;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            [$this, 'getInputFieldNameTypeResolvers'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            [$this, 'getInputFieldDescription'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            [$this, 'getInputFieldTypeModifiers'],
            10,
            3
        );
        $this->getHooksAPI()->addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            [$this, 'getInputFieldFilterInput'],
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
        ?array $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?array {
        if (!($inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'authorIDs' => [UsersCustomPostsFilterInputProcessor::class, UsersCustomPostsFilterInputProcessor::FILTERINPUT_AUTHOR_IDS],
            'excludeAuthorIDs' => [UsersCustomPostsFilterInputProcessor::class, UsersCustomPostsFilterInputProcessor::FILTERINPUT_EXCLUDE_AUTHOR_IDS],
            'customPostAuthorIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_CUSTOMPOST_AUTHOR_IDS],
            'excludeCustomPostAuthorIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS],
            default => $inputFieldFilterInput,
        };
    }
}
