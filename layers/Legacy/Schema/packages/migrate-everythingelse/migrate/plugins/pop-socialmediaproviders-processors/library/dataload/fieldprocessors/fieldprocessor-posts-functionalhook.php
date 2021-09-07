<?php

use PoPSchema\CustomPosts\TypeResolvers\Interface\IsCustomPostInterfaceTypeResolver;

class PoP_SocialMediaProviders_DataLoad_FunctionalFieldResolver_PostSocialMediaItems extends PoP_SocialMediaProviders_DataLoad_FieldResolver_FunctionalSocialMediaItems
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostInterfaceTypeResolver::class,
        ];
    }

    protected function getTitleField()
    {
        return 'title';
    }
}

// Static Initialization: Attach
(new PoP_SocialMediaProviders_DataLoad_FunctionalFieldResolver_PostSocialMediaItems())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
