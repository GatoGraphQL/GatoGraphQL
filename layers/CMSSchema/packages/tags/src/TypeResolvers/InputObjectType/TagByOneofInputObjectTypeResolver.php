<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagByOneofInputObjectTypeResolver;

class TagByOneofInputObjectTypeResolver extends AbstractTagByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'TagByFilterInput';
    }
}
