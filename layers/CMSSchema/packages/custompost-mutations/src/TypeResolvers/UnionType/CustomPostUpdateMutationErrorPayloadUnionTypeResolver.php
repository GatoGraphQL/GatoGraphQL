<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader $customPostUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader(CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader $customPostUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostUpdateMutationErrorPayloadUnionTypeDataLoader = $customPostUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader(): CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostUpdateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a custom post (using nested mutations)', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
