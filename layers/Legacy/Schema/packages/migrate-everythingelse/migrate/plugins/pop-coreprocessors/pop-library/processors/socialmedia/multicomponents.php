<?php

class PoP_Module_Processor_SocialMediaMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA = 'multicomponent-post-sm';
    public final const COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA = 'multicomponent-user-sm';
    public final const COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA = 'multicomponent-tag-sm';
    public final const COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS = 'multicomponent-postsecinteractions';
    public final const COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS = 'multicomponent-usersecinteractions';
    public final const COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS = 'multicomponent-tagsecinteractions';
    public final const COMPONENT_MULTICOMPONENT_POSTOPTIONS = 'multicomponent-postoptions';
    public final const COMPONENT_MULTICOMPONENT_USEROPTIONS = 'multicomponent-useroptions';
    public final const COMPONENT_MULTICOMPONENT_TAGOPTIONS = 'multicomponent-tagoptions';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA],
            [self::class, self::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA],
            [self::class, self::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA],
            [self::class, self::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS],
            [self::class, self::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS],
            [self::class, self::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS],
            [self::class, self::COMPONENT_MULTICOMPONENT_POSTOPTIONS],
            [self::class, self::COMPONENT_MULTICOMPONENT_USEROPTIONS],
            [self::class, self::COMPONENT_MULTICOMPONENT_TAGOPTIONS],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        $components = array();
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA:
            case self::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA:
            case self::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA:
                break;

            case self::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS:
                $components[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::COMPONENT_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::COMPONENT_MULTICOMPONENT_POSTOPTIONS:
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA];
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS];
                break;

            case self::COMPONENT_MULTICOMPONENT_USEROPTIONS:
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA];
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS];
                break;

            case self::COMPONENT_MULTICOMPONENT_TAGOPTIONS:
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA];
                $components[] = [self::class, self::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS];
                break;
        }

        // Allow PoP Generic Forms Processors to add modules
        $components = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $components,
            $component
        );
        $ret = array_merge(
            $ret,
            $components
        );

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_POSTSOCIALMEDIA:
            case self::COMPONENT_MULTICOMPONENT_USERSOCIALMEDIA:
            case self::COMPONENT_MULTICOMPONENT_TAGSOCIALMEDIA:
                $this->appendProp($component, $props, 'class', 'sm-group');
                break;

            case self::COMPONENT_MULTICOMPONENT_POSTSECINTERACTIONS:
            case self::COMPONENT_MULTICOMPONENT_USERSECINTERACTIONS:
            case self::COMPONENT_MULTICOMPONENT_TAGSECINTERACTIONS:
                $this->appendProp($component, $props, 'class', 'secinteractions-group');
                break;

            case self::COMPONENT_MULTICOMPONENT_POSTOPTIONS:
            case self::COMPONENT_MULTICOMPONENT_USEROPTIONS:
            case self::COMPONENT_MULTICOMPONENT_TAGOPTIONS:
                $this->appendProp($component, $props, 'class', 'options-group');
                foreach ($this->getSubComponents($component) as $subComponent) {
                    $this->appendProp([$subComponent], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



