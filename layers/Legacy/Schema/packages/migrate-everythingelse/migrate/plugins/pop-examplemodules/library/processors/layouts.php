<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;

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
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

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
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFields($component);

        switch ($component->name) {
            case self::COMPONENT_EXAMPLE_COMMENT:
                $ret[] = new RelationalComponentField(
                    'author',
                    [
                        self::COMPONENT_EXAMPLE_AUTHORPROPERTIES,
                    ]
                );
                break;
        }

        return $ret;
    }
}

