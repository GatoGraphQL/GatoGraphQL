<?php

declare(strict_types=1);

namespace PoPSchema\Comments\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Comments\TypeDataLoaders\CommentTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class CommentTypeResolver extends AbstractTypeResolver
{
    public const NAME = 'Comment';

    public function getTypeName(): string
    {
        return self::NAME;
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Comments added to posts', 'comments');
    }

    public function getID(object $resultItem)
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
