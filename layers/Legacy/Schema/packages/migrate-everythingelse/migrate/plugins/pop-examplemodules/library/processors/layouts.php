<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class ComponentProcessor_Layouts extends AbstractComponentProcessor
{
    public final const COMPONENT_EXAMPLE_404 = 'example-404';
    public final const COMPONENT_EXAMPLE_HOMEWELCOME = 'example-homewelcome';
    public final const COMPONENT_EXAMPLE_COMMENT = 'example-comment';
    public final const COMPONENT_EXAMPLE_AUTHORPROPERTIES = 'example-authorproperties';
    public final const COMPONENT_EXAMPLE_TAGPROPERTIES = 'example-tagproperties';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_EXAMPLE_404,
            self::COMPONENT_EXAMPLE_HOMEWELCOME,
            self::COMPONENT_EXAMPLE_COMMENT,
            self::COMPONENT_EXAMPLE_AUTHORPROPERTIES,
            self::COMPONENT_EXAMPLE_TAGPROPERTIES,
        );
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_COMMENT:
                $ret[] = 'content';
                break;

            case self::COMPONENT_EXAMPLE_AUTHORPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('displayName', 'description', 'url')
                );
                break;

            case self::COMPONENT_EXAMPLE_TAGPROPERTIES:
                $ret = array_merge(
                    $ret,
                    array('name', 'slug', 'description', 'count')
                );
                break;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentFieldNode[]
     */
    public function getRelationalComponentFieldNodes(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFieldNodes($component);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_COMMENT:
                $ret[] = new RelationalComponentFieldNode(
                    new LeafField(
                        'author',
                        null,
                        [],
                        [],
                        LocationHelper::getNonSpecificLocation()
                    ),
                    [
                        self::COMPONENT_EXAMPLE_AUTHORPROPERTIES,
                    ]
                );
                break;
        }

        return $ret;
    }
}

