<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaKeyWithValueDoesNotExistErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getTermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver(): TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver */
            $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TermMetaKeyWithValueDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
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
