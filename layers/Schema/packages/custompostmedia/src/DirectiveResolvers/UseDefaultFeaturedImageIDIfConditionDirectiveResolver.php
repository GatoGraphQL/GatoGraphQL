<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\DirectiveResolvers;

use PoPSchema\CustomPostMedia\Environment;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\BasicDirectives\DirectiveResolvers\AbstractUseDefaultValueIfConditionDirectiveResolver;

class UseDefaultFeaturedImageIDIfConditionDirectiveResolver extends AbstractUseDefaultValueIfConditionDirectiveResolver
{
    public function getDirectiveName(): string
    {
        return 'defaultFeaturedImage';
    }

    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }
    public function getFieldNamesToApplyTo(): array
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
