<?php
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_CommonPages_EM_Module_Processor_CustomScrollMapSectionDataloads extends GD_EM_Module_Processor_ScrollMapDataloadsBase
{
    public final const COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP = 'dataload-whoweare-scrollmap';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inner_components = array(
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP => [GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::class, GD_CommonPages_EM_Module_Processor_CustomScrollMapSections::COMPONENT_SCROLLMAP_WHOWEARE_SCROLLMAP],
        );

        return $inner_components[$component->name] ?? null;
    }

    protected function showFetchmore(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP:
                return false;
        }

        return parent::showFetchmore($component);
    }

    public function getFormat(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $maps = array(
            self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP,
        );
        if (in_array($component, $maps)) {
            $format = POP_FORMAT_MAP;
        }

        return $format ?? parent::getFormat($component);
    }

    public function getRelationalTypeResolver(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($component);
    }

    public function getDatasource(\PoP\ComponentModel\Component\Component $component, array &$props): string
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP:
                return \PoP\ComponentModel\Constants\DataSources::IMMUTABLE;
        }

        return parent::getDatasource($component, $props);
    }

    public function getObjectIDOrIDs(\PoP\ComponentModel\Component\Component $component, array &$props, &$data_properties): string|int|array
    {
        switch ($component->name) {
            case self::COMPONENT_DATALOAD_WHOWEARE_SCROLLMAP:
                return getWhoweareCoreUserIds();
        }

        return parent::getObjectIDOrIDs($component, $props, $data_properties);
    }
}



