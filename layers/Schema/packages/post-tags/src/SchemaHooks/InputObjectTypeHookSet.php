<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\SchemaHooks;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;
use PoPSchema\Tags\FilterInputProcessors\FilterInputProcessor;

class InputObjectTypeHookSet extends AbstractHookSet
{
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
        if (!($inputObjectTypeResolver instanceof PostsFilterInputObjectTypeResolverInterface)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'tagIDs' => $this->getIDScalarTypeResolver(),
                'tagSlugs' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof PostsFilterInputObjectTypeResolverInterface)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'tagIDs' => $this->__('Get results from the tags with given IDs', 'pop-users'),
            'tagSlugs' => $this->__('Get results from the tags with given slug', 'pop-users'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldTypeModifiers(
        int $inputFieldTypeModifiers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): int {
        if (!($inputObjectTypeResolver instanceof PostsFilterInputObjectTypeResolverInterface)) {
            return $inputFieldTypeModifiers;
        }
        return match ($inputFieldName) {
            'tagIDs',
            'tagSlugs'
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
        if (!($inputObjectTypeResolver instanceof PostsFilterInputObjectTypeResolverInterface)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'tagIDs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_IDS],
            'tagSlugs' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_TAG_SLUGS],
            default => $inputFieldFilterInput,
        };
    }
}
