<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

class CustomPostByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'CustomPostByInput';
    }
}
