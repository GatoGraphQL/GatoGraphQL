<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver(): TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver */
            $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTermMetaKeyDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TermMetaKeyDoesNotExistErrorPayload::class;
    }
}
