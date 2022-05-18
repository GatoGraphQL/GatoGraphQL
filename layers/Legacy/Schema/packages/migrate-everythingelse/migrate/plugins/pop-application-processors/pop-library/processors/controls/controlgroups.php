<?php

class PoP_Module_Processor_CustomControlGroups extends PoP_Module_Processor_ControlGroupsBase
{
    public final const COMPONENT_CONTROLGROUP_COMMENTS = 'controlgroup-comments';
    public final const COMPONENT_CONTROLGROUP_TAGLIST = 'controlgroup-taglist';
    public final const COMPONENT_CONTROLGROUP_POSTLIST = 'controlgroup-postlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST = 'controlgroup-blockauthorpostlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKPOSTLIST = 'controlgroup-blockpostlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKRELOAD = 'controlgroup-blockreload';
    public final const COMPONENT_CONTROLGROUP_BLOCKLOADLATEST = 'controlgroup-blockloadlatest';
    public final const COMPONENT_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST = 'controlgroup-quickviewblockpostlist';
    public final const COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST = 'controlgroup-submenupostlist';
    public final const COMPONENT_CONTROLGROUP_SUBMENUPOSTLISTMAIN = 'controlgroup-submenupostlistmain';
    public final const COMPONENT_CONTROLGROUP_USERLIST = 'controlgroup-userlist';
    public final const COMPONENT_CONTROLGROUP_BLOCKUSERLIST = 'controlgroup-blockuserlist';
    public final const COMPONENT_CONTROLGROUP_SUBMENUUSERLIST = 'controlgroup-submenuuserlist';
    public final const COMPONENT_CONTROLGROUP_SUBMENUUSERLISTMAIN = 'controlgroup-submenuuserlistmain';
    public final const COMPONENT_CONTROLGROUP_SUBMENUSHARE = 'controlgroup-submenushare';
    public final const COMPONENT_CONTROLGROUP_SHARE = 'controlgroup-share';
    public final const COMPONENT_CONTROLGROUP_MYPOSTLIST = 'controlgroup-mypostlist';
    public final const COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST = 'controlgroup-myblockcustompostlist';
    public final const COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST = 'controlgroup-mycustompostlist';
    public final const COMPONENT_CONTROLGROUP_MYBLOCKPOSTLIST = 'controlgroup-myblockpostlist';
    public final const COMPONENT_CONTROLGROUP_ACCOUNT = 'controlgroup-account';
    public final const COMPONENT_CONTROLGROUP_CREATEACCOUNT = 'controlgroup-createaccount';
    public final const COMPONENT_CONTROLGROUP_CREATEPOST = 'controlgroup-createpost';
    public final const COMPONENT_CONTROLGROUP_CREATERESETPOST = 'controlgroup-createresetpost';
    public final const COMPONENT_CONTROLGROUP_EDITPOST = 'controlgroup-editpost';
    public final const COMPONENT_CONTROLGROUP_USERPOSTINTERACTION = 'controlgroup-userpostinteraction';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CONTROLGROUP_COMMENTS],
            [self::class, self::COMPONENT_CONTROLGROUP_TAGLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_POSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKRELOAD],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKLOADLATEST],
            [self::class, self::COMPONENT_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_SUBMENUPOSTLISTMAIN],
            [self::class, self::COMPONENT_CONTROLGROUP_USERLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_BLOCKUSERLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_SUBMENUUSERLISTMAIN],
            [self::class, self::COMPONENT_CONTROLGROUP_SUBMENUSHARE],
            [self::class, self::COMPONENT_CONTROLGROUP_SHARE],
            [self::class, self::COMPONENT_CONTROLGROUP_MYPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_MYBLOCKPOSTLIST],
            [self::class, self::COMPONENT_CONTROLGROUP_ACCOUNT],
            [self::class, self::COMPONENT_CONTROLGROUP_CREATEACCOUNT],
            [self::class, self::COMPONENT_CONTROLGROUP_CREATEPOST],
            [self::class, self::COMPONENT_CONTROLGROUP_CREATERESETPOST],
            [self::class, self::COMPONENT_CONTROLGROUP_EDITPOST],
            [self::class, self::COMPONENT_CONTROLGROUP_USERPOSTINTERACTION],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_CONTROLGROUP_COMMENTS:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;
                
            case self::COMPONENT_CONTROLGROUP_POSTLIST:
            case self::COMPONENT_CONTROLGROUP_USERLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_BLOCKAUTHORPOSTLIST:
                // Allow URE to add the Switch Organization/Organization+Members if the author is an organization
                $layouts = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomControlGroups:blockauthorpostlist:layouts',
                    array(
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER],
                        [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE]
                    )
                );
                if ($layouts) {
                    $ret = array_merge(
                        $ret,
                        $layouts
                    );
                }
                break;

            case self::COMPONENT_CONTROLGROUP_BLOCKPOSTLIST:
            case self::COMPONENT_CONTROLGROUP_BLOCKUSERLIST:
            case self::COMPONENT_CONTROLGROUP_TAGLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_BLOCKRELOAD:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                break;

            case self::COMPONENT_CONTROLGROUP_BLOCKLOADLATEST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_LOADLATESTBLOCK];
                break;

            case self::COMPONENT_CONTROLGROUP_QUICKVIEWBLOCKPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_CURRENTURL];
                break;

            case self::COMPONENT_CONTROLGROUP_SUBMENUPOSTLIST:
            case self::COMPONENT_CONTROLGROUP_SUBMENUUSERLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_SUBMENUPOSTLISTMAIN:
            case self::COMPONENT_CONTROLGROUP_SUBMENUUSERLISTMAIN:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESULTSSHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_SUBMENUSHARE:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_SHARE:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SHARE];
                break;

            case self::COMPONENT_CONTROLGROUP_MYCUSTOMPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_MYBLOCKCUSTOMPOSTLIST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_MYPOSTLIST:
                $ret[] = [PoP_Module_Processor_CustomControlButtonGroups::class, PoP_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDPOST];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCKGROUP];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_MYBLOCKPOSTLIST:
                $ret[] = [PoP_Module_Processor_CustomControlButtonGroups::class, PoP_Module_Processor_CustomControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_ADDPOST];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RELOADBLOCK];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_FILTER];
                break;

            case self::COMPONENT_CONTROLGROUP_ACCOUNT:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS];
                break;

            case self::COMPONENT_CONTROLGROUP_CREATEACCOUNT:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_TOGGLEOPTIONALFIELDS];
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_SUBMENU_XS];
                break;

            case self::COMPONENT_CONTROLGROUP_CREATEPOST:
            case self::COMPONENT_CONTROLGROUP_EDITPOST:
                // Empty because their inner layouts will be added through the hook below, by PoP Section Processors
                break;

            case self::COMPONENT_CONTROLGROUP_CREATERESETPOST:
                $ret[] = [PoP_Module_Processor_ControlButtonGroups::class, PoP_Module_Processor_ControlButtonGroups::COMPONENT_CONTROLBUTTONGROUP_RESETEDITOR];
                break;

            case self::COMPONENT_CONTROLGROUP_USERPOSTINTERACTION:
                // Allow TPPDebate to add the "What do you think about TPP?" before these layouts
                if ($layouts = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CustomControlGroups:userpostinteraction:layouts',
                    array(
                        [PoP_Module_Processor_AddCommentPostViewComponentButtons::class, PoP_Module_Processor_AddCommentPostViewComponentButtons::COMPONENT_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT],
                    ),
                    $component
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
        if ($layouts = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_CustomControlGroups:layouts',
            array(),
            $component
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


