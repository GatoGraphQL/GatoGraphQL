<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_SocialMediaProviders_DataLoad_FunctionalObjectTypeFieldResolver_UserSocialMediaItems extends PoP_SocialMediaProviders_DataLoad_ObjectTypeFieldResolver_FunctionalSocialMediaItems
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    protected function getTitleField()
    {
        return 'displayName';
    }
}

// Static Initialization: Attach
(new PoP_SocialMediaProviders_DataLoad_FunctionalObjectTypeFieldResolver_UserSocialMediaItems())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
