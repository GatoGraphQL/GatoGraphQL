<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeResolvers\ObjectType;

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
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\RelationalTypeDataLoaders\ObjectType\MenuTypeDataLoader;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected MenuTypeDataLoader $menuTypeDataLoader;
    protected MenuTypeAPIInterface $menuTypeAPI;

    #[Required]
    public function autowireMenuObjectTypeResolver(
        MenuTypeDataLoader $menuTypeDataLoader,
        MenuTypeAPIInterface $menuTypeAPI,
    ) {
        $this->menuTypeDataLoader = $menuTypeDataLoader;
        $this->menuTypeAPI = $menuTypeAPI;
    }

    public function getTypeName(): string
    {
        return 'Menu';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a navigation menu', 'menus');
    }

    public function getID(object $object): string | int | null
    {
        $menu = $object;
        return $this->menuTypeAPI->getMenuID($menu);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->menuTypeDataLoader;
    }
}
