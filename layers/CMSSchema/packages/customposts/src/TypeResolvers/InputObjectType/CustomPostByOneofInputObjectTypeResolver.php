<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

class CustomPostByOneofInputObjectTypeResolver extends AbstractCustomPostByOneofInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostByInput';
    }
}
