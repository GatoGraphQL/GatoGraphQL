<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\AbstractPostsFilterInputObjectTypeResolver;
use PoPWPSchema\Posts\FilterInputs\IsStickyFilterInput;

class InputObjectTypeHookSet extends AbstractHookSet
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IsStickyFilterInput $isStickyFilterInput = null;

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getIsStickyFilterInput(): IsStickyFilterInput
    {
        if ($this->isStickyFilterInput === null) {
            /** @var IsStickyFilterInput */
            $isStickyFilterInput = $this->instanceManager->getInstance(IsStickyFilterInput::class);
            $this->isStickyFilterInput = $isStickyFilterInput;
        }
        return $this->isStickyFilterInput;
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
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'isSticky' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'isSticky' => $this->__('Filter by sticky posts', 'posts'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'isSticky' => $this->getIsStickyFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
