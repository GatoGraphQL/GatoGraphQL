<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;

class MenuByInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
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

    public function getTypeName(): string
    {
        return 'MenuByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a menu', 'menus');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by menu ID', 'menus'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
