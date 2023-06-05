<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\UnionType;

use PoPWPSchema\Blocks\TypeResolvers\InterfaceType\BlockInterfaceTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;

abstract class AbstractBlockUnionTypeResolver extends AbstractUnionTypeResolver
{
    private ?BlockInterfaceTypeResolver $errorPayloadInterfaceTypeResolver = null;

    final public function setBlockInterfaceTypeResolver(BlockInterfaceTypeResolver $errorPayloadInterfaceTypeResolver): void
    {
        $this->errorPayloadInterfaceTypeResolver = $errorPayloadInterfaceTypeResolver;
    }
    final protected function getBlockInterfaceTypeResolver(): BlockInterfaceTypeResolver
    {
        /** @var BlockInterfaceTypeResolver */
        return $this->errorPayloadInterfaceTypeResolver ??= $this->instanceManager->getInstance(BlockInterfaceTypeResolver::class);
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [
            $this->getBlockInterfaceTypeResolver(),
        ];
    }
}
