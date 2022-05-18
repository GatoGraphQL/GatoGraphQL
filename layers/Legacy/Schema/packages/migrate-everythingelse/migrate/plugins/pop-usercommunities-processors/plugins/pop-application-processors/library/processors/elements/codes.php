<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_UserCommunities_Module_Processor_Codes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_INVITENEWMEMBERSHELP = 'code-invitenewmembershelp';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_INVITENEWMEMBERSHELP],
        );
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CODE_INVITENEWMEMBERSHELP:
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $invitenew_processor = $componentprocessor_manager->getProcessor([GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS]);
                $invitenew = sprintf(
                    '<a class="btn btn-xs btn-success" href="%s" target="%s"><i class="fa fa-fw %s"></i>%s</a>',
                    $invitenew_processor->getHref([GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS], $props),
                    $invitenew_processor->getTarget([GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS], $props),
                    $invitenew_processor->getFontawesome([GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS], $props),
                    $invitenew_processor->getLabel([GD_URE_Module_Processor_CustomAnchorControls::class, GD_URE_Module_Processor_CustomAnchorControls::MODULE_ANCHORCONTROL_INVITENEWMEMBERS], $props)
                );
                $placeholder = '<strong>%s</strong>: <br/>%s';
                $placeholder_item = '<h3>%s</h3>%s';
                $placeholder_li = '<li>%s</li>';
                
                $help_header = sprintf(
                    '<i class="fa fa-fw fa-info"></i>%s',
                    TranslationAPIFacade::getInstance()->__('Show Help', 'poptheme-wassup')
                );
                $help_body =
                sprintf(
                    $placeholder_item,
                    TranslationAPIFacade::getInstance()->__('Inviting users to become your members', 'poptheme-wassup'),
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('Please click on %s to invite your community\'s members to become your members in the website.', 'poptheme-wassup'),
                        $invitenew
                    )
                ).
                sprintf(
                    $placeholder_item,
                    TranslationAPIFacade::getInstance()->__('What is a member?', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('This depends on your community. Some examples:<br/>', 'poptheme-wassup').
                    '<ul>'.
                    sprintf($placeholder_li, TranslationAPIFacade::getInstance()->__('University teachers/students', 'poptheme-wassup')).
                    sprintf($placeholder_li, TranslationAPIFacade::getInstance()->__('NGO volunteers', 'poptheme-wassup')).
                    sprintf($placeholder_li, TranslationAPIFacade::getInstance()->__('Company staff', 'poptheme-wassup')).
                    sprintf($placeholder_li, TranslationAPIFacade::getInstance()->__('etc', 'poptheme-wassup')).
                    '</ul>'
                ).
                sprintf(
                    $placeholder_item,
                    TranslationAPIFacade::getInstance()->__('What happens when my community has members?', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('If they have privilege <em>Contribute content</em> on, whenever they post any content in the website (eg: a new event, article, project, etc) will also show up under your community\'s profile. If this privilege is off, they will just appear under "My members"', 'poptheme-wassup')
                ).
                sprintf(
                    $placeholder_item,
                    TranslationAPIFacade::getInstance()->__('Editing current members\' status', 'poptheme-wassup'),
                    TranslationAPIFacade::getInstance()->__('The users listed below have declared themselves to be members of your community. You can click on "Edit Membership" to edit the settings for each one of them:', 'poptheme-wassup').
                    sprintf(
                        '<ul>%s%s%s</ul>',
                        sprintf(
                            $placeholder_li,
                            sprintf(
                                $placeholder,
                                TranslationAPIFacade::getInstance()->__('Status', 'poptheme-wassup'),
                                TranslationAPIFacade::getInstance()->__('<em>Active</em> if the user is truly your member, or <em>Rejected</em> otherwise.<br/><em>Rejected</em> users will not appear as your community\'s members, or contribute content.', 'poptheme-wassup')
                            )
                        ),
                        sprintf(
                            $placeholder_li,
                            sprintf(
                                $placeholder,
                                TranslationAPIFacade::getInstance()->__('Privileges', 'poptheme-wassup'),
                                TranslationAPIFacade::getInstance()->__('<em>Contribute content</em> will add the member\'s content to your profile.', 'poptheme-wassup')
                            )
                        ),
                        sprintf(
                            $placeholder_li,
                            sprintf(
                                $placeholder,
                                TranslationAPIFacade::getInstance()->__('Tags', 'poptheme-wassup'),
                                TranslationAPIFacade::getInstance()->__('What is the type of relationship from this member to your community.', 'poptheme-wassup')
                            )
                        )
                    )
                );
                
                $placeholder_list = '<div class="panel panel-info"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" href="#block-mymembers-description-%1$s" aria-expanded="false" aria-controls="block-mymembers-description-%1$s">%2$s</a></h4></div><div id="block-mymembers-description-%1$s" class="panel-body collapse">%3$s</div></div>';
                return sprintf(
                    '<div class="panel-group">%s</div>',
                    sprintf(
                        $placeholder_list,
                        'help',
                        $help_header,
                        $help_body
                    )
                );
        }
    
        return parent::getCode($module, $props);
    }
}


