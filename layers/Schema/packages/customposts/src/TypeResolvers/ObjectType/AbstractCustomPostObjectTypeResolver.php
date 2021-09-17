<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

abstract class AbstractCustomPostObjectTypeResolver extends AbstractObjectTypeResolver implements CustomPostObjectTypeResolverInterface
{
    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $object): string | int | null
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getID($object);
    }
}
