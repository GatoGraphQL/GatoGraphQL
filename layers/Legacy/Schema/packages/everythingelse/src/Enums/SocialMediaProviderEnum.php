<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\Enums;

use PoP\ComponentModel\Enums\AbstractEnum;

class SocialMediaProviderEnum extends AbstractEnum
{
    protected function getEnumName(): string
    {
        return 'SocialMediaProvider';
    }
    public function getValues(): array
    {
        return [
            'facebook' => \GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
            'linkedin' => \GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
            'twitter' => \GD_SOCIALMEDIA_PROVIDER_TWITTER,
        ];
    }
}
