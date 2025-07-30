<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

abstract class AbstractGenericCustomPostObjectTypeFieldResolver extends AbstractWithParentCustomPostObjectTypeFieldResolver
{
    protected function getCustomPostFieldTypeResolver(): ConcreteTypeResolverInterface
    {
        return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
    }
}
