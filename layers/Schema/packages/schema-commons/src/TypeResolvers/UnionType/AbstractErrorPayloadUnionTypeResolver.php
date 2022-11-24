<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\UnionType;

use PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\IsErrorPayloadInterfaceTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;

abstract class AbstractErrorPayloadUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?IsErrorPayloadInterfaceTypeResolver $isErrorPayloadInterfaceTypeResolver = null;

    final public function setIsErrorPayloadInterfaceTypeResolver(IsErrorPayloadInterfaceTypeResolver $isErrorPayloadInterfaceTypeResolver): void
    {
        $this->isErrorPayloadInterfaceTypeResolver = $isErrorPayloadInterfaceTypeResolver;
    }
    final protected function getIsErrorPayloadInterfaceTypeResolver(): IsErrorPayloadInterfaceTypeResolver
    {
        /** @var IsErrorPayloadInterfaceTypeResolver */
        return $this->isErrorPayloadInterfaceTypeResolver ??= $this->instanceManager->getInstance(IsErrorPayloadInterfaceTypeResolver::class);
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getIsErrorPayloadInterfaceTypeResolver(),
        ];
    }
}
