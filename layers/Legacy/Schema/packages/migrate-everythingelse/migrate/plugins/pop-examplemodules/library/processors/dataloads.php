<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\ComponentProcessors\AbstractDataloadComponentProcessor;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\State\ApplicationState;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\QueriedObject\ComponentProcessors\QueriedDBObjectComponentProcessorTrait;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class ComponentProcessor_Dataloads extends AbstractDataloadComponentProcessor
{
    use QueriedDBObjectComponentProcessorTrait;

    public final const COMPONENT_EXAMPLE_LATESTPOSTS = 'example-latestposts';
    public final const COMPONENT_EXAMPLE_AUTHORLATESTPOSTS = 'example-authorlatestposts';
    public final const COMPONENT_EXAMPLE_AUTHORDESCRIPTION = 'example-authordescription';
    public final const COMPONENT_EXAMPLE_TAGLATESTPOSTS = 'example-taglatestposts';
    public final const COMPONENT_EXAMPLE_TAGDESCRIPTION = 'example-tagdescription';
    public final const COMPONENT_EXAMPLE_SINGLE = 'example-single';
    public final const COMPONENT_EXAMPLE_PAGE = 'example-page';
    public final const COMPONENT_EXAMPLE_HOMESTATICPAGE = 'example-homestaticpage';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EXAMPLE_LATESTPOSTS,
            self::COMPONENT_EXAMPLE_AUTHORLATESTPOSTS,
            self::COMPONENT_EXAMPLE_AUTHORDESCRIPTION,
            self::COMPONENT_EXAMPLE_TAGLATESTPOSTS,
            self::COMPONENT_EXAMPLE_TAGDESCRIPTION,
            self::COMPONENT_EXAMPLE_SINGLE,
            self::COMPONENT_EXAMPLE_PAGE,
            self::COMPONENT_EXAMPLE_HOMESTATICPAGE,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_AUTHORDESCRIPTION:
                $ret[] = [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::COMPONENT_EXAMPLE_AUTHORPROPERTIES];
                break;

            case self::COMPONENT_EXAMPLE_TAGDESCRIPTION:
                $ret[] = [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::COMPONENT_EXAMPLE_TAGPROPERTIES];
                break;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string | int | array
    {
        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_SINGLE:
            case self::COMPONENT_EXAMPLE_PAGE:
            case self::COMPONENT_EXAMPLE_TAGDESCRIPTION:
            case self::COMPONENT_EXAMPLE_AUTHORDESCRIPTION:
                return $this->getQueriedDBObjectID($component, $props, $data_properties);
            case self::COMPONENT_EXAMPLE_HOMESTATICPAGE:
                $pageTypeAPI = PageTypeAPIFacade::getInstance();
                return $pageTypeAPI->getHomeStaticPageID();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_LATESTPOSTS:
            case self::COMPONENT_EXAMPLE_AUTHORLATESTPOSTS:
            case self::COMPONENT_EXAMPLE_TAGLATESTPOSTS:
            case self::COMPONENT_EXAMPLE_SINGLE:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);

            case self::COMPONENT_EXAMPLE_AUTHORDESCRIPTION:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::COMPONENT_EXAMPLE_TAGDESCRIPTION:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);

            case self::COMPONENT_EXAMPLE_PAGE:
            case self::COMPONENT_EXAMPLE_HOMESTATICPAGE:
                return $this->instanceManager->getInstance(PageObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    protected function getMutableonrequestDataloadQueryArgs(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($component, $props);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_AUTHORLATESTPOSTS:
                $ret['authors'] = [\PoP\Root\App::getState(['routing', 'queried-object-id'])];
                break;

            case self::COMPONENT_EXAMPLE_TAGLATESTPOSTS:
                $ret['tag-ids'] = [\PoP\Root\App::getState(['routing', 'queried-object-id'])];
                break;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFields($component);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_SINGLE:
            case self::COMPONENT_EXAMPLE_LATESTPOSTS:
            case self::COMPONENT_EXAMPLE_AUTHORLATESTPOSTS:
            case self::COMPONENT_EXAMPLE_TAGLATESTPOSTS:
                $ret[] = new RelationalComponentField(
                    new LeafField(
                        'author',
                        null,
                        [],
                        [],
                        LocationHelper::getNonSpecificLocation()
                    ),
                    [
                        [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::COMPONENT_EXAMPLE_AUTHORPROPERTIES],
                    ]
                );
                $ret[] = new RelationalComponentField(
                    new LeafField(
                        'comments',
                        null,
                        [],
                        [],
                        LocationHelper::getNonSpecificLocation()
                    ),
                    [
                        [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::COMPONENT_EXAMPLE_COMMENT],
                    ]
                );
                break;
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $data_fields = array(
            self::COMPONENT_EXAMPLE_LATESTPOSTS => array('title', 'content', 'url'),
            self::COMPONENT_EXAMPLE_AUTHORLATESTPOSTS => array('title', 'content', 'url'),
            self::COMPONENT_EXAMPLE_TAGLATESTPOSTS => array('title', 'content', 'url'),
            self::COMPONENT_EXAMPLE_SINGLE => array('title', 'content', 'excerpt', 'status', 'date', 'commentCount', 'customPostType', 'catSlugs', 'tagNames'),
            self::COMPONENT_EXAMPLE_PAGE => array('title', 'content', 'date'),
            self::COMPONENT_EXAMPLE_HOMESTATICPAGE => array('title', 'content', 'date'),
        );
        return array_merge(
            parent::getLeafComponentFields($component, $props),
            $data_fields[$component->name] ?? array()
        );
    }
}

