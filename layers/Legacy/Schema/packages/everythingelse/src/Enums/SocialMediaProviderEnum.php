<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnumTypeResolver;

class SocialMediaProviderEnum extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'SocialMediaProvider';
    }
    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return [
            'facebook' => \GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
            'linkedin' => \GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
            'twitter' => \GD_SOCIALMEDIA_PROVIDER_TWITTER,
        ];
    }
}
