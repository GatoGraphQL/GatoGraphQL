<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSchema\Users\RelationalTypeDataLoaders\ObjectType\UserTypeDataLoader;

class UserObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected UserTypeAPIInterface $userTypeAPI;
    protected UserTypeDataLoader $userTypeDataLoader;

    #[Required]
    public function autowireUserObjectTypeResolver(
        UserTypeAPIInterface $userTypeAPI,
        UserTypeDataLoader $userTypeDataLoader,
    ) {
        $this->userTypeAPI = $userTypeAPI;
        $this->userTypeDataLoader = $userTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'User';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a user', 'users');
    }

    public function getID(object $object): string | int | null
    {
        $user = $object;
        return $this->userTypeAPI->getUserId($user);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->userTypeDataLoader;
    }
}
