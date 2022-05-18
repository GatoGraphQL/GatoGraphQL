<?php

class PoP_Module_Processor_SocialMediaMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_POSTSOCIALMEDIA = 'multicomponent-post-sm';
    public final const MODULE_MULTICOMPONENT_USERSOCIALMEDIA = 'multicomponent-user-sm';
    public final const MODULE_MULTICOMPONENT_TAGSOCIALMEDIA = 'multicomponent-tag-sm';
    public final const MODULE_MULTICOMPONENT_POSTSECINTERACTIONS = 'multicomponent-postsecinteractions';
    public final const MODULE_MULTICOMPONENT_USERSECINTERACTIONS = 'multicomponent-usersecinteractions';
    public final const MODULE_MULTICOMPONENT_TAGSECINTERACTIONS = 'multicomponent-tagsecinteractions';
    public final const MODULE_MULTICOMPONENT_POSTOPTIONS = 'multicomponent-postoptions';
    public final const MODULE_MULTICOMPONENT_USEROPTIONS = 'multicomponent-useroptions';
    public final const MODULE_MULTICOMPONENT_TAGOPTIONS = 'multicomponent-tagoptions';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA],
            [self::class, self::MODULE_MULTICOMPONENT_USERSOCIALMEDIA],
            [self::class, self::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA],
            [self::class, self::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS],
            [self::class, self::MODULE_MULTICOMPONENT_USERSECINTERACTIONS],
            [self::class, self::MODULE_MULTICOMPONENT_TAGSECINTERACTIONS],
            [self::class, self::MODULE_MULTICOMPONENT_POSTOPTIONS],
            [self::class, self::MODULE_MULTICOMPONENT_USEROPTIONS],
            [self::class, self::MODULE_MULTICOMPONENT_TAGOPTIONS],
        );
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        $modules = array();
        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA:
            case self::MODULE_MULTICOMPONENT_USERSOCIALMEDIA:
            case self::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA:
                break;

            case self::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::MODULE_MULTICOMPONENT_USERSECINTERACTIONS:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::MODULE_MULTICOMPONENT_TAGSECINTERACTIONS:
                $modules[] = [PoP_Module_Processor_Buttons::class, PoP_Module_Processor_Buttons::MODULE_BUTTON_PRINT_SOCIALMEDIA];
                break;

            case self::MODULE_MULTICOMPONENT_POSTOPTIONS:
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA];
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS];
                break;

            case self::MODULE_MULTICOMPONENT_USEROPTIONS:
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_USERSOCIALMEDIA];
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_USERSECINTERACTIONS];
                break;

            case self::MODULE_MULTICOMPONENT_TAGOPTIONS:
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA];
                $modules[] = [self::class, self::MODULE_MULTICOMPONENT_TAGSECINTERACTIONS];
                break;
        }

        // Allow PoP Generic Forms Processors to add modules
        $modules = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_SocialMediaMultipleComponents:modules',
            $modules,
            $componentVariation
        );
        $ret = array_merge(
            $ret,
            $modules
        );

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_MULTICOMPONENT_POSTSOCIALMEDIA:
            case self::MODULE_MULTICOMPONENT_USERSOCIALMEDIA:
            case self::MODULE_MULTICOMPONENT_TAGSOCIALMEDIA:
                $this->appendProp($componentVariation, $props, 'class', 'sm-group');
                break;

            case self::MODULE_MULTICOMPONENT_POSTSECINTERACTIONS:
            case self::MODULE_MULTICOMPONENT_USERSECINTERACTIONS:
            case self::MODULE_MULTICOMPONENT_TAGSECINTERACTIONS:
                $this->appendProp($componentVariation, $props, 'class', 'secinteractions-group');
                break;

            case self::MODULE_MULTICOMPONENT_POSTOPTIONS:
            case self::MODULE_MULTICOMPONENT_USEROPTIONS:
            case self::MODULE_MULTICOMPONENT_TAGOPTIONS:
                $this->appendProp($componentVariation, $props, 'class', 'options-group');
                foreach ($this->getSubComponentVariations($componentVariation) as $submodule) {
                    $this->appendProp([$submodule], $props, 'class', 'inline');
                }
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



