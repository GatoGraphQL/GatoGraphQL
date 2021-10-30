<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\AbstractInterfaceTypeResolver;

class CommentableInterfaceTypeResolver extends AbstractInterfaceTypeResolver
{
    public function getTypeName(): string
    {
        return 'Commentable';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('The entity can receive comments', 'comments');
    }
}
