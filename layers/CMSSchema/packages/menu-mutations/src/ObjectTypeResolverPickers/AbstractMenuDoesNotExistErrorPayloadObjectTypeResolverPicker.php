<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MenuMutations\ObjectModels\MenuDoesNotExistErrorPayload;
use PoPCMSSchema\MenuMutations\TypeResolvers\ObjectType\MenuDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractMenuDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?MenuDoesNotExistErrorPayloadObjectTypeResolver $menuDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getMenuDoesNotExistErrorPayloadObjectTypeResolver(): MenuDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->menuDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var MenuDoesNotExistErrorPayloadObjectTypeResolver */
            $menuDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(MenuDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->menuDoesNotExistErrorPayloadObjectTypeResolver = $menuDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->menuDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getMenuDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return MenuDoesNotExistErrorPayload::class;
    }
}
