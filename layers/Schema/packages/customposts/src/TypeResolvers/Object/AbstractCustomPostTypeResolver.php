<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\Object;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;

abstract class AbstractCustomPostTypeResolver extends AbstractObjectTypeResolver
{
    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $resultItem): string | int | null
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getID($resultItem);
    }
}
