<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType\CustomPostMetaQueryInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    private ?CustomPostMetaQueryInputObjectTypeResolver $customPostMetaQueryInputObjectTypeResolver = null;

    final protected function getCustomPostMetaQueryInputObjectTypeResolver(): CustomPostMetaQueryInputObjectTypeResolver
    {
        if ($this->customPostMetaQueryInputObjectTypeResolver === null) {
            /** @var CustomPostMetaQueryInputObjectTypeResolver */
            $customPostMetaQueryInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostMetaQueryInputObjectTypeResolver::class);
            $this->customPostMetaQueryInputObjectTypeResolver = $customPostMetaQueryInputObjectTypeResolver;
        }
        return $this->customPostMetaQueryInputObjectTypeResolver;
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getCustomPostMetaQueryInputObjectTypeResolver();
    }

    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver;
    }
}
