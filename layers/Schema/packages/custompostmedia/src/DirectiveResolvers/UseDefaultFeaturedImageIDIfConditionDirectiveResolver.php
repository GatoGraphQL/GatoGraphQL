<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\DirectiveResolvers;

use PoPSchema\CustomPostMedia\Environment;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\BasicDirectives\DirectiveResolvers\AbstractUseDefaultValueIfConditionDirectiveResolver;

class UseDefaultFeaturedImageIDIfConditionDirectiveResolver extends AbstractUseDefaultValueIfConditionDirectiveResolver
{
    const DIRECTIVE_NAME = 'defaultFeaturedImage';
    public static function getDirectiveName(): string
    {
        return self::DIRECTIVE_NAME;
    }

    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }
    public static function getFieldNamesToApplyTo(): array
    {
        return [
            'featuredImage',
        ];
    }

    protected function getDefaultValue()
    {
        return Environment::getDefaultFeaturedImageID();
    }
}
