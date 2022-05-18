<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_ButtonInners extends PoP_Module_Processor_ButtonInnersBase
{
    public final const MODULE_BUTTONINNER_STANCE_CREATE = 'buttoninner-stance-create';
    public final const MODULE_BUTTONINNER_STANCE_UPDATE = 'buttoninner-stance-update';
    public final const MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE = 'lazybuttoninner-stance-createorupdate';
    public final const MODULE_BUTTONINNER_POSTSTANCE_PRO = 'buttoninner-poststance-pro';
    public final const MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL = 'buttoninner-poststance-neutral';
    public final const MODULE_BUTTONINNER_POSTSTANCE_AGAINST = 'buttoninner-poststance-against';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTONINNER_STANCE_CREATE],
            [self::class, self::MODULE_BUTTONINNER_STANCE_UPDATE],
            [self::class, self::MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE],
            [self::class, self::MODULE_BUTTONINNER_POSTSTANCE_PRO],
            [self::class, self::MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL],
            [self::class, self::MODULE_BUTTONINNER_POSTSTANCE_AGAINST],
        );
    }

    public function getFontawesome(array $component, array &$props)
    {
        $routes = array(
            self::MODULE_BUTTONINNER_STANCE_CREATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE => POP_USERSTANCE_ROUTE_ADDSTANCE,
            self::MODULE_BUTTONINNER_STANCE_UPDATE => POP_USERSTANCE_ROUTE_EDITSTANCE,
            self::MODULE_BUTTONINNER_POSTSTANCE_PRO => POP_USERSTANCE_ROUTE_STANCES_PRO,
            self::MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
            self::MODULE_BUTTONINNER_POSTSTANCE_AGAINST => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
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
            self::MODULE_BUTTONINNER_STANCE_CREATE => $stance,
            self::MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE => $stance.GD_CONSTANT_LOADING_SPINNER,
            self::MODULE_BUTTONINNER_STANCE_UPDATE => sprintf(
                TranslationAPIFacade::getInstance()->__('Edit your corresponding %s', 'pop-userstance-processors'),
                PoP_UserStance_PostNameUtils::getNameLc()
            ),
            self::MODULE_BUTTONINNER_POSTSTANCE_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors'),
            self::MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors'),
            self::MODULE_BUTTONINNER_POSTSTANCE_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors'),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getBtnTitle($component);
    }

    public function getTextField(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_BUTTONINNER_POSTSTANCE_PRO:
                return 'stanceProCount';

            case self::MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL:
                return 'stanceNeutralCount';

            case self::MODULE_BUTTONINNER_POSTSTANCE_AGAINST:
                return 'stanceAgainstCount';
        }

        return parent::getTextField($component, $props);
    }
}


