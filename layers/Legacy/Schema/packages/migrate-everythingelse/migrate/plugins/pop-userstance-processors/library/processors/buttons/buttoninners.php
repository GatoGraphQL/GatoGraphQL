<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const COMPONENT_BUTTONINNER_STANCE_CREATE = 'buttoninner-stance-create';
    public final const COMPONENT_BUTTONINNER_STANCE_UPDATE = 'buttoninner-stance-update';
    public final const COMPONENT_LAZYBUTTONINNER_STANCE_CREATEORUPDATE = 'lazybuttoninner-stance-createorupdate';
    public final const COMPONENT_BUTTONINNER_POSTSTANCE_PRO = 'buttoninner-poststance-pro';
    public final const COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL = 'buttoninner-poststance-neutral';
    public final const COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST = 'buttoninner-poststance-against';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTONINNER_STANCE_CREATE],
            [self::class, self::COMPONENT_BUTTONINNER_STANCE_UPDATE],
            [self::class, self::COMPONENT_LAZYBUTTONINNER_STANCE_CREATEORUPDATE],
            [self::class, self::COMPONENT_BUTTONINNER_POSTSTANCE_PRO],
            [self::class, self::COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL],
            [self::class, self::COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::COMPONENT_BUTTONINNER_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::COMPONENT_LAZYBUTTONINNER_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::COMPONENT_BUTTONINNER_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            self::COMPONENT_BUTTONINNER_POSTSTANCE_PRO => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
        );
        if ($route = $routes[$component[1]] ?? null) {
            return 'fa-fw '.getRouteIcon($route, false);
        }

        return parent::getFontawesome($component, $props);
    }

    public function getBtnTitle(array $component)
    {

        // Allow Events to have a different title
        $stance = sprintf(
            TranslationAPIFacade::getInstance()->__('%s, %s', 'pop-userstance-processors'),
            UserStance_Module_Processor_ButtonUtils::getFullviewAddstanceTitle(),
            PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle('lc')
        );
        $titles = array(
            self::COMPONENT_BUTTONINNER_STANCE_CREATE => $stance,
            self::COMPONENT_LAZYBUTTONINNER_STANCE_CREATEORUPDATE => $stance.GD_CONSTANT_LOADING_SPINNER,
            self::COMPONENT_BUTTONINNER_STANCE_UPDATE => sprintf(
                TranslationAPIFacade::getInstance()->__('Edit your corresponding %s', 'pop-userstance-processors'),
                PoP_UserStance_PostNameUtils::getNameLc()
            ),
            self::COMPONENT_BUTTONINNER_POSTSTANCE_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors'),
            self::COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors'),
            self::COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }

    public function getTextField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTONINNER_POSTSTANCE_PRO:
                return 'stanceProCount';

            case self::COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL:
                return 'stanceNeutralCount';

            case self::COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST:
                return 'stanceAgainstCount';
        }

        return parent::getTextField($component, $props);
    }
}


