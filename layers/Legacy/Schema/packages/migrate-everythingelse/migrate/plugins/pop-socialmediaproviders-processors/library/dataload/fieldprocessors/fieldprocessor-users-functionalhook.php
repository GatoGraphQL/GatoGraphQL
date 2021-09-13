<?php
use PoPSchema\Users\TypeResolvers\ObjectType\UserTypeResolver;

class PoP_SocialMediaProviders_DataLoad_FunctionalObjectTypeFieldResolver_UserSocialMediaItems extends PoP_SocialMediaProviders_DataLoad_ObjectTypeFieldResolver_FunctionalSocialMediaItems
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserTypeResolver::class,
        ];
    }

    protected function getTitleField()
    {
        return 'displayName';
    }
}

// Static Initialization: Attach
(new PoP_SocialMediaProviders_DataLoad_FunctionalObjectTypeFieldResolver_UserSocialMediaItems())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
