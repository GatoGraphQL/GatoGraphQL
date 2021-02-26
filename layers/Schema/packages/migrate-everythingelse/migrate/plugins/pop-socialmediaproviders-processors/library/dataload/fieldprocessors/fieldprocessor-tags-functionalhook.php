<?php
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;

class PoP_SocialMediaProviders_DataLoad_FunctionalFieldResolver_TagSocialMediaItems extends PoP_SocialMediaProviders_DataLoad_FieldResolver_FunctionalSocialMediaItems
{
    public function getClassesToAttachTo(): array
    {
        return array(
            PostTagTypeResolver::class,
        );
    }

    protected function getTitleField()
    {
        return 'name';
    }
}

// Static Initialization: Attach
(new PoP_SocialMediaProviders_DataLoad_FunctionalFieldResolver_TagSocialMediaItems())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
