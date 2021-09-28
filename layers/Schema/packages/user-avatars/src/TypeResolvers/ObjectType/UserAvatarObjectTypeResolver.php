<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\UserAvatars\ObjectModels\UserAvatar;
use PoPSchema\UserAvatars\RelationalTypeDataLoaders\ObjectType\UserAvatarTypeDataLoader;

class UserAvatarObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected UserAvatarTypeDataLoader $userAvatarTypeDataLoader;

    #[Required]
    public function autowireUserAvatarObjectTypeResolver(
        UserAvatarTypeDataLoader $userAvatarTypeDataLoader,
    ): void {
        $this->userAvatarTypeDataLoader = $userAvatarTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserAvatar';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('User avatar', 'user-avatars');
    }

    public function getID(object $object): string | int | null
    {
        /** @var UserAvatar */
        $userAvatar = $object;
        return $userAvatar->id;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->userAvatarTypeDataLoader;
    }
}
