<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyWithValueDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver(): TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver */
            $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->entityMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TermMetaKeyWithValueDoesNotExistErrorPayload::class;
    }
}
