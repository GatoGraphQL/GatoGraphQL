<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType\CustomPostCreateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostCreateMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?CustomPostCreateMutationErrorPayloadUnionTypeDataLoader $customPostCreateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCustomPostCreateMutationErrorPayloadUnionTypeDataLoader(CustomPostCreateMutationErrorPayloadUnionTypeDataLoader $customPostCreateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostCreateMutationErrorPayloadUnionTypeDataLoader = $customPostCreateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostCreateMutationErrorPayloadUnionTypeDataLoader(): CustomPostCreateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostCreateMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostCreateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostCreateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostCreateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostCreateMutationErrorPayloadUnionTypeDataLoader();
    }
}
