<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput;

abstract class AbstractAddTagFilterInputObjectTypeHookSet extends AbstractHookSet
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TagSlugsFilterInput $tagSlugsFilterInput = null;
    private ?TagIDsFilterInput $tagIDsFilterInput = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setTagSlugsFilterInput(TagSlugsFilterInput $tagSlugsFilterInput): void
    {
        $this->tagSlugsFilterInput = $tagSlugsFilterInput;
    }
    final protected function getTagSlugsFilterInput(): TagSlugsFilterInput
    {
        /** @var TagSlugsFilterInput */
        return $this->tagSlugsFilterInput ??= $this->instanceManager->getInstance(TagSlugsFilterInput::class);
    }
    final public function setTagIDsFilterInput(TagIDsFilterInput $tagIDsFilterInput): void
    {
        $this->tagIDsFilterInput = $tagIDsFilterInput;
    }
    final protected function getTagIDsFilterInput(): TagIDsFilterInput
    {
        /** @var TagIDsFilterInput */
        return $this->tagIDsFilterInput ??= $this->instanceManager->getInstance(TagIDsFilterInput::class);
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
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(
        array $inputFieldNameTypeResolvers,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
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

    abstract protected function getInputObjectTypeResolverClass(): string;

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
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
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
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
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!(is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), true))) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'tagIDs' => $this->getTagIDsFilterInput(),
            'tagSlugs' => $this->getTagSlugsFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
