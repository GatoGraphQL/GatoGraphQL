<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeResolvers\ObjectType;

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
use PoPSchema\UserRolesWP\RelationalTypeDataLoaders\ObjectType\UserRoleTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

class UserRoleObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected UserRoleTypeDataLoader $userRoleTypeDataLoader;

    #[Required]
    public function autowireUserRoleObjectTypeResolver(
        UserRoleTypeDataLoader $userRoleTypeDataLoader,
    ) {
        $this->userRoleTypeDataLoader = $userRoleTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserRole';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('User roles', 'user-roles');
    }

    public function getID(object $object): string | int | null
    {
        $role = $object;
        return $role->name;
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->userRoleTypeDataLoader;
    }
}
