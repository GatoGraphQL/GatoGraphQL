<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostDateQueryInputObjectTypeResolver;

abstract class AbstractCustomPostsFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?CustomPostDateQueryInputObjectTypeResolver $customPostDateQueryInputObjectTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setCustomPostDateQueryInputObjectTypeResolver(CustomPostDateQueryInputObjectTypeResolver $customPostDateQueryInputObjectTypeResolver): void
    {
        $this->customPostDateQueryInputObjectTypeResolver = $customPostDateQueryInputObjectTypeResolver;
    }
    final protected function getCustomPostDateQueryInputObjectTypeResolver(): CustomPostDateQueryInputObjectTypeResolver
    {
        return $this->customPostDateQueryInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostDateQueryInputObjectTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'search' => $this->getStringScalarTypeResolver(),
            'dateQuery' => $this->getCustomPostDateQueryInputObjectTypeResolver(),
        ];
    }
}
