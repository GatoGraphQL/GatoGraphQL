<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Application_Module_Processor_UserCheckpointMessageLayouts extends PoP_Module_Processor_CheckpointMessageLayoutsBase
{
    public final const COMPONENT_LAYOUT_CHECKPOINTMESSAGE_DOMAIN = 'layout-checkpointmessage-domain';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_DOMAIN,
        );
    }

    public function getMessages(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getMessages($component, $props);

        switch ($component->name) {
            case self::COMPONENT_LAYOUT_CHECKPOINTMESSAGE_DOMAIN:
                $ret['domainempty'] = TranslationAPIFacade::getInstance()->__('The domain is empty.', 'pop-coreprocessors');
                $ret['domainnotvalid'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Domain <strong>%s</strong> is not valid.', 'pop-coreprocessors'),
                    PoP_Application_Utils::getRequestDomain()
                );
                break;
        }

        return $ret;
    }
}



