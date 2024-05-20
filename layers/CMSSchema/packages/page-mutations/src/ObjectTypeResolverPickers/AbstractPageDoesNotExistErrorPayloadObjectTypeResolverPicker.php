<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\PageMutations\ObjectModels\PageDoesNotExistErrorPayload;
use PoPCMSSchema\PageMutations\TypeResolvers\ObjectType\PageDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractPageDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?PageDoesNotExistErrorPayloadObjectTypeResolver $pageDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setPageDoesNotExistErrorPayloadObjectTypeResolver(PageDoesNotExistErrorPayloadObjectTypeResolver $pageDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->pageDoesNotExistErrorPayloadObjectTypeResolver = $pageDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getPageDoesNotExistErrorPayloadObjectTypeResolver(): PageDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->pageDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var PageDoesNotExistErrorPayloadObjectTypeResolver */
            $pageDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(PageDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->pageDoesNotExistErrorPayloadObjectTypeResolver = $pageDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->pageDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getPageDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return PageDoesNotExistErrorPayload::class;
    }
}
