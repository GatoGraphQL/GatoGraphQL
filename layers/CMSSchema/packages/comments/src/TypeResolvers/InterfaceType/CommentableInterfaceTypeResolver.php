<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class CommentableInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Commentable';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('The entity can receive comments', 'comments');
    }
}
