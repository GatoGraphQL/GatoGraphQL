<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public const MODULE_CONTROLGROUP_COMMENTS = 'controlgroup-comments';
    public const MODULE_CONTROLGROUP_TAGLIST = 'controlgroup-taglist';
    public const MODULE_CONTROLGROUP_POSTLIST = 'controlgroup-postlist';
    public const MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST = 'controlgroup-blockauthorpostlist';
    public const MODULE_CONTROLGROUP_BLOCKPOSTLIST = 'controlgroup-blockpostlist';
    public const MODULE_CONTROLGROUP_BLOCKRELOAD = 'controlgroup-blockreload';
    public const MODULE_CONTROLGROUP_BLOCKLOADLATEST = 'controlgroup-blockloadlatest';
    public const MODULE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST = 'controlgroup-quickviewblockpostlist';
    public const MODULE_CONTROLGROUP_SUBMENUPOSTLIST = 'controlgroup-submenupostlist';
    public const MODULE_CONTROLGROUP_SUBMENUPOSTLISTMAIN = 'controlgroup-submenupostlistmain';
    public const MODULE_CONTROLGROUP_USERLIST = 'controlgroup-userlist';
    public const MODULE_CONTROLGROUP_BLOCKUSERLIST = 'controlgroup-blockuserlist';
    public const MODULE_CONTROLGROUP_SUBMENUUSERLIST = 'controlgroup-submenuuserlist';
    public const MODULE_CONTROLGROUP_SUBMENUUSERLISTMAIN = 'controlgroup-submenuuserlistmain';
    public const MODULE_CONTROLGROUP_SUBMENUSHARE = 'controlgroup-submenushare';
    public const MODULE_CONTROLGROUP_SHARE = 'controlgroup-share';
    public const MODULE_CONTROLGROUP_MYPOSTLIST = 'controlgroup-mypostlist';
    public const MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST = 'controlgroup-myblockcustompostlist';
    public const MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST = 'controlgroup-mycustompostlist';
    public const MODULE_CONTROLGROUP_MYBLOCKPOSTLIST = 'controlgroup-myblockpostlist';
    public const MODULE_CONTROLGROUP_ACCOUNT = 'controlgroup-account';
    public const MODULE_CONTROLGROUP_CREATEACCOUNT = 'controlgroup-createaccount';
    public const MODULE_CONTROLGROUP_CREATEPOST = 'controlgroup-createpost';
    public const MODULE_CONTROLGROUP_CREATERESETPOST = 'controlgroup-createresetpost';
    public const MODULE_CONTROLGROUP_EDITPOST = 'controlgroup-editpost';
    public const MODULE_CONTROLGROUP_USERPOSTINTERACTION = 'controlgroup-userpostinteraction';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CONTROLGROUP_COMMENTS],
            [self::class, self::MODULE_CONTROLGROUP_TAGLIST],
            [self::class, self::MODULE_CONTROLGROUP_POSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKRELOAD],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKLOADLATEST],
            [self::class, self::MODULE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_SUBMENUPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_SUBMENUPOSTLISTMAIN],
            [self::class, self::MODULE_CONTROLGROUP_USERLIST],
            [self::class, self::MODULE_CONTROLGROUP_BLOCKUSERLIST],
            [self::class, self::MODULE_CONTROLGROUP_SUBMENUUSERLIST],
            [self::class, self::MODULE_CONTROLGROUP_SUBMENUUSERLISTMAIN],
            [self::class, self::MODULE_CONTROLGROUP_SUBMENUSHARE],
            [self::class, self::MODULE_CONTROLGROUP_SHARE],
            [self::class, self::MODULE_CONTROLGROUP_MYPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_MYBLOCKPOSTLIST],
            [self::class, self::MODULE_CONTROLGROUP_ACCOUNT],
            [self::class, self::MODULE_CONTROLGROUP_CREATEACCOUNT],
            [self::class, self::MODULE_CONTROLGROUP_CREATEPOST],
            [self::class, self::MODULE_CONTROLGROUP_CREATERESETPOST],
            [self::class, self::MODULE_CONTROLGROUP_EDITPOST],
            [self::class, self::MODULE_CONTROLGROUP_USERPOSTINTERACTION],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_CONTROLGROUP_COMMENTS:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;
                
            case self::MODULE_CONTROLGROUP_POSTLIST:
            case self::MODULE_CONTROLGROUP_USERLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::MODULE_CONTROLGROUP_BLOCKAUTHORPOSTLIST:
                // Allow URE to add the Switch Organization/Organization+Members if the author is an organization
                $layouts = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomControlGroups:blockauthorpostlist:layouts',
                    array(
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE]
                    )
                );
                if ($layouts) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;

            case self::MODULE_CONTROLGROUP_BLOCKPOSTLIST:
            case self::MODULE_CONTROLGROUP_BLOCKUSERLIST:
            case self::MODULE_CONTROLGROUP_TAGLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::MODULE_CONTROLGROUP_BLOCKRELOAD:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                break;

            case self::MODULE_CONTROLGROUP_BLOCKLOADLATEST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_LOADLATESTBLOCK];
                break;

            case self::MODULE_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_CURRENTURL];
                break;

            case self::MODULE_CONTROLGROUP_SUBMENUPOSTLIST:
            case self::MODULE_CONTROLGROUP_SUBMENUUSERLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::MODULE_CONTROLGROUP_SUBMENUPOSTLISTMAIN:
            case self::MODULE_CONTROLGROUP_SUBMENUUSERLISTMAIN:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::MODULE_CONTROLGROUP_SUBMENUSHARE:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SHARE];
                break;

            case self::MODULE_CONTROLGROUP_SHARE:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SHARE];
                break;

            case self::MODULE_CONTROLGROUP_MYCUSTOMPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_CONTROLGROUP_MYPOSTLIST:
                $ret[] = [PoP_Module_Processor_CustomControlButtonGroups::class, PoP_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDPOST];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_CONTROLGROUP_MYBLOCKPOSTLIST:
                $ret[] = [PoP_Module_Processor_CustomControlButtonGroups::class, PoP_Module_Processor_CustomControlButtonGroups::MODULE_CONTROLBUTTONGROUP_ADDPOST];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::MODULE_CONTROLGROUP_ACCOUNT:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS];
                break;

            case self::MODULE_CONTROLGROUP_CREATEACCOUNT:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_SUBMENU_XS];
                break;

            case self::MODULE_CONTROLGROUP_CREATEPOST:
            case self::MODULE_CONTROLGROUP_EDITPOST:
                // Empty because their inner layouts will be added through the hook below, by PoP Section Processors
                break;

            case self::MODULE_CONTROLGROUP_CREATERESETPOST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::MODULE_CONTROLBUTTONGROUP_RESETEDITOR];
                break;

            case self::MODULE_CONTROLGROUP_USERPOSTINTERACTION:
                // Allow TPPDebate to add the "What do you think about TPP?" before these layouts
                if ($layouts = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CustomControlGroups:userpostinteraction:layouts',
                    array(
                        [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::MODULE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT],
                    ),
                    $module
                )
                ) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;
        }

        // Allow PoP Section Processors to add the FAQ buttons
        if ($layouts = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Module_Processor_CustomControlGroups:layouts',
            array(),
            $module
        )
        ) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }
}


