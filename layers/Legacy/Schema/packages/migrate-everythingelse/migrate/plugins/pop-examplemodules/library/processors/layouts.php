<?php
namespace PoP\ExampleModules;

use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\ComponentModel\ComponentProcessors\AbstractComponentProcessor;

class ComponentProcessor_Layouts extends AbstractComponentProcessor
{
    public final const COMPONENT_EXAMPLE_404 = 'example-404';
    public final const COMPONENT_EXAMPLE_HOMEWELCOME = 'example-homewelcome';
    public final const COMPONENT_EXAMPLE_COMMENT = 'example-comment';
    public final const COMPONENT_EXAMPLE_AUTHORPROPERTIES = 'example-authorproperties';
    public final const COMPONENT_EXAMPLE_TAGPROPERTIES = 'example-tagproperties';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_EXAMPLE_404],
            [self::class, self::COMPONENT_EXAMPLE_HOMEWELCOME],
            [self::class, self::COMPONENT_EXAMPLE_COMMENT],
            [self::class, self::COMPONENT_EXAMPLE_AUTHORPROPERTIES],
            [self::class, self::COMPONENT_EXAMPLE_TAGPROPERTIES],
        );
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        switch ($component[1]) {
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
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        $ret = parent::getRelationalSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_EXAMPLE_COMMENT:
                $ret[] = new RelationalModuleField(
                    'author',
                    [
                        [self::class, self::COMPONENT_EXAMPLE_AUTHORPROPERTIES],
                    ]
                );
                break;
        }

        return $ret;
    }
}

