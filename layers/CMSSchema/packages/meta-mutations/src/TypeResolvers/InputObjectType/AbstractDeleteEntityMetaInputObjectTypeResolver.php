<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MetaMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

abstract class AbstractDeleteEntityMetaInputObjectTypeResolver extends AbstractInputObjectTypeResolver implements DeleteEntityMetaInputObjectTypeResolverInterface
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver = null;

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
    final protected function getAnyScalarScalarTypeResolver(): AnyScalarScalarTypeResolver
    {
        if ($this->anyScalarScalarTypeResolver === null) {
            /** @var AnyScalarScalarTypeResolver */
            $anyScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyScalarScalarTypeResolver::class);
            $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
        }
        return $this->anyScalarScalarTypeResolver;
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to delete an entity\'s meta entry', 'meta-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            $this->addIDInputField() ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::KEY => $this->getStringScalarTypeResolver(),
                MutationInputProperties::VALUE => $this->getAnyScalarScalarTypeResolver(),
            ]
        );
    }

    abstract protected function addIDInputField(): bool;

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID => $this->__('The ID of the entity', 'meta-mutations'),
            MutationInputProperties::KEY => $this->__('The meta key', 'meta-mutations'),
            MutationInputProperties::VALUE => $this->__('The specific meta value to delete, to avoid removing duplicate entries for that key', 'meta-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::ID,
            MutationInputProperties::KEY
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
