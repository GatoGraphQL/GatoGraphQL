<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class PoP_UserCommunities_Module_Processor_MySectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT = 'dataload-mymembers-table-edit';
    public final const MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW = 'dataload-mymembers-scroll-fullviewpreview';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT],
            [self::class, self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT => POP_USERCOMMUNITIES_ROUTE_MYMEMBERS,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    protected function getInnerSubmodules(array $module): array
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
                $ret[] = [PoP_UserCommunities_Module_Processor_Codes::class, PoP_UserCommunities_Module_Processor_Codes::MODULE_CODE_INVITENEWMEMBERSHELP];
                $ret[] = [GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS_BIG];
                $ret[] = [PoP_UserCommunities_Module_Processor_Tables::class, PoP_UserCommunities_Module_Processor_Tables::MODULE_TABLE_MYMEMBERS];
                break;

            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                $ret[] = [PoP_UserCommunities_Module_Processor_CustomScrolls::class, PoP_UserCommunities_Module_Processor_CustomScrolls::MODULE_SCROLL_MYMEMBERS_FULLVIEWPREVIEW];
                break;
        }

        return $ret;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                $this->addJsmethod($ret, 'destroyPageOnUserLoggedOut');
                $this->addJsmethod($ret, 'refetchBlockOnUserLoggedIn');
                break;
        }

        return $ret;
    }

    public function getFilterSubmodule(array $module): ?array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                return [GD_URE_Module_Processor_CustomFilters::class, GD_URE_Module_Processor_CustomFilters::MODULE_FILTER_MYMEMBERS];
        }

        return parent::getFilterSubmodule($module);
    }

    public function getFormat(array $module): ?string
    {

        // Add the format attr
        $tables = array(
            [self::class, self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW],
        );
        if (in_array($module, $tables)) {
            $format = POP_FORMAT_TABLE;
        } elseif (in_array($module, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        }

        return $format ?? parent::getFormat($module);
    }

    protected function getMutableonrequestDataloadQueryArgs(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($module, $props);

        switch ($module[1]) {
         // Members of the Community
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                $current_user = \PoP\Root\App::getState('current-user-id');
                if (gdUreIsCommunity($current_user)) {
                    $ret['meta-query'][] = [
                        'key' => \PoPCMSSchema\UserMeta\Utils::getMetaKey(GD_URE_METAKEY_PROFILE_COMMUNITIES),
                        'value' => $current_user,
                        'compare' => 'IN',
                    ];
                }
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    protected function getCheckpointmessageModule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                return [GD_UserCommunities_Module_Processor_UserCheckpointMessages::class, GD_UserCommunities_Module_Processor_UserCheckpointMessages::MODULE_CHECKPOINTMESSAGE_PROFILECOMMUNITY];
        }

        return parent::getCheckpointmessageModule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_MYMEMBERS_TABLE_EDIT:
            case self::MODULE_DATALOAD_MYMEMBERS_SCROLL_FULLVIEW:
                $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', TranslationAPIFacade::getInstance()->__('members', 'poptheme-wassup'));
                $this->setProp([GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::class, GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY], $props, 'action', TranslationAPIFacade::getInstance()->__('view your members', 'poptheme-wassup'));
                break;
        }

        parent::initModelProps($module, $props);
    }
}



