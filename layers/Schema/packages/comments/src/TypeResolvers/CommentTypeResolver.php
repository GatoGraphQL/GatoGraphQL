<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Comments\TypeDataLoaders\CommentTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class CommentTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Comment';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Comments added to posts', 'comments');
    }

    public function getID(object $resultItem): mixed
    {
        $cmscommentsresolver = \PoPSchema\Comments\ObjectPropertyResolverFactory::getInstance();
        $comment = $resultItem;
        return $cmscommentsresolver->getCommentId($comment);
    }

    public function getTypeDataLoaderClass(): string
    {
        return CommentTypeDataLoader::class;
    }
}
