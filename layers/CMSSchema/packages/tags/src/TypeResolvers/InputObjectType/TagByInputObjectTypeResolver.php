<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagByInputObjectTypeResolver;

class TagByInputObjectTypeResolver extends AbstractTagByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagByInput';
    }
}
