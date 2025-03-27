<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\MetaMutations\ObjectModels\TermMetaAlreadyHasSingleEntryErrorPayload;
use PoPCMSSchema\MetaMutations\TypeResolvers\ObjectType\TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = null;

    final protected function getTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver(): TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver
    {
        if ($this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver === null) {
            /** @var TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver */
            $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(TermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver::class);
            $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver = $termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
        }
        return $this->termMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getTermMetaAlreadyHasSingleEntryErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return TermMetaAlreadyHasSingleEntryErrorPayload::class;
    }
}
