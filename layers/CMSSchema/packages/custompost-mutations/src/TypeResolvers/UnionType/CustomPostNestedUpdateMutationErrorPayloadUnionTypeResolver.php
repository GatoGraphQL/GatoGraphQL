<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader $customPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader(CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader $customPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader = $customPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader(): CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostNestedUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post (using nested mutations)', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
