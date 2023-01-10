<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\UnionType;

use PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\ErrorPayloadInterfaceTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;

abstract class AbstractErrorPayloadUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?ErrorPayloadInterfaceTypeResolver $errorPayloadInterfaceTypeResolver = null;

    final public function setErrorPayloadInterfaceTypeResolver(ErrorPayloadInterfaceTypeResolver $errorPayloadInterfaceTypeResolver): void
    {
        $this->errorPayloadInterfaceTypeResolver = $errorPayloadInterfaceTypeResolver;
    }
    final protected function getErrorPayloadInterfaceTypeResolver(): ErrorPayloadInterfaceTypeResolver
    {
        /** @var ErrorPayloadInterfaceTypeResolver */
        return $this->errorPayloadInterfaceTypeResolver ??= $this->instanceManager->getInstance(ErrorPayloadInterfaceTypeResolver::class);
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getErrorPayloadInterfaceTypeResolver(),
        ];
    }
}
