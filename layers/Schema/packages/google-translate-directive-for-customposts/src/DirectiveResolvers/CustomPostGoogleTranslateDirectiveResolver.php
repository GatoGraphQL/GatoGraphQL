<?php

declare(strict_types=1);

namespace PoPSchema\GoogleTranslateDirectiveForCustomPosts\DirectiveResolvers;

use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\GoogleTranslateDirective\DirectiveResolvers\AbstractGoogleTranslateDirectiveResolver;

class CustomPostGoogleTranslateDirectiveResolver extends AbstractGoogleTranslateDirectiveResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }
    public static function getFieldNamesToApplyTo(): array
    {
        return [
            'title',
            'content',
            'excerpt',
        ];
    }
}
