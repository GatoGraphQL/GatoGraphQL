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
    public function getEnumValues(): array
    {
        return [
            \GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
            \GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
            \GD_SOCIALMEDIA_PROVIDER_TWITTER,
        ];
    }
}
