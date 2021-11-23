<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;

class PageByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    public function getTypeName(): string
    {
        return 'PageByInput';
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->getTranslationAPI()->__('a page', 'pages');
    }
}
