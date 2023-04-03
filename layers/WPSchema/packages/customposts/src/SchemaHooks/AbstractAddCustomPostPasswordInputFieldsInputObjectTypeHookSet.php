<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPWPSchema\CustomPosts\FilterInputs\HasPasswordFilterInput;
use PoPWPSchema\CustomPosts\FilterInputs\PasswordFilterInput;

abstract class AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?HasPasswordFilterInput $hasPasswordFilterInput = null;
    private ?PasswordFilterInput $passwordFilterInput = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
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
    final public function setHasPasswordFilterInput(HasPasswordFilterInput $hasPasswordFilterInput): void
    {
        $this->hasPasswordFilterInput = $hasPasswordFilterInput;
    }
    final protected function getHasPasswordFilterInput(): HasPasswordFilterInput
    {
        /** @var HasPasswordFilterInput */
        return $this->hasPasswordFilterInput ??= $this->instanceManager->getInstance(HasPasswordFilterInput::class);
    }
    final public function setPasswordFilterInput(PasswordFilterInput $passwordFilterInput): void
    {
        $this->passwordFilterInput = $passwordFilterInput;
    }
    final protected function getPasswordFilterInput(): PasswordFilterInput
    {
        /** @var PasswordFilterInput */
        return $this->passwordFilterInput ??= $this->instanceManager->getInstance(PasswordFilterInput::class);
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
            HookNames::SENSITIVE_INPUT_FIELD_NAMES,
            $this->getSensitiveInputFieldNames(...),
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
            HookNames::INPUT_FIELD_DEFAULT_VALUE,
            $this->getInputFieldDefaultValue(...),
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
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'hasPassword' => $this->getBooleanScalarTypeResolver(),
                'password' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    abstract protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool;

    /**
     * @param string[] $inputFieldNames
     * @return string[]
     */
    public function getSensitiveInputFieldNames(
        array $inputFieldNames,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): array {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNames;
        }
        return array_merge(
            $inputFieldNames,
            [
                'hasPassword',
                'password',
            ]
        );
    }

    public function getInputFieldDescription(
        ?string $inputFieldDescription,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): ?string {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return match ($inputFieldName) {
            'hasPassword' => $this->__('Indicate if to include custom posts which are password-protected. Pass `null` to fetch both with/out password', 'customposts'),
            'password' => $this->__('Include custom posts protected by a specific password', 'customposts'),
            default => $inputFieldDescription,
        };
    }

    public function getInputFieldDefaultValue(
        mixed $inputFieldDefaultValue,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName
    ): mixed {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDefaultValue;
        }
        return match ($inputFieldName) {
            'hasPassword' => false,
            default => $inputFieldDefaultValue,
        };
    }

    public function getInputFieldFilterInput(
        ?FilterInputInterface $inputFieldFilterInput,
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
        string $inputFieldName,
    ): ?FilterInputInterface {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        return match ($inputFieldName) {
            'hasPassword' => $this->getHasPasswordFilterInput(),
            'password' => $this->getPasswordFilterInput(),
            default => $inputFieldFilterInput,
        };
    }
}
