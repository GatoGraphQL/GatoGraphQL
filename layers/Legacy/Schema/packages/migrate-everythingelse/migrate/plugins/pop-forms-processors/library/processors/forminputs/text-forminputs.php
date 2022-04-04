<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TARGETURL = 'forminput-targeturl';
    public final const MODULE_FORMINPUT_TARGETTITLE = 'forminput-targettitle';
    public final const MODULE_FORMINPUT_POSTTITLE = 'forminput-posttitle';
    public final const MODULE_FORMINPUT_USERNICENAME = 'forminput-usernicename';
    public final const MODULE_FORMINPUT_SENDERNAME = 'forminput-sendername';
    public final const MODULE_FORMINPUT_BROWSERURL = 'forminput-browserurl';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TARGETURL],
            [self::class, self::MODULE_FORMINPUT_TARGETTITLE],
            [self::class, self::MODULE_FORMINPUT_POSTTITLE],
            [self::class, self::MODULE_FORMINPUT_USERNICENAME],
            [self::class, self::MODULE_FORMINPUT_BROWSERURL],
            [self::class, self::MODULE_FORMINPUT_SENDERNAME],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_SENDERNAME:
                return TranslationAPIFacade::getInstance()->__('Your name', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getPagesectionJsmethod(array $module, array &$props)
    {
        $ret = parent::getPagesectionJsmethod($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TARGETTITLE:
                // fill the input when showing the modal
                $this->addJsmethod($ret, 'fillModalInput');
                break;

            case self::MODULE_FORMINPUT_TARGETURL:
                // fill the input when showing the modal
                $this->addJsmethod($ret, 'fillModalURLInput');
                break;
        }

        return $ret;
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_POSTTITLE:
                return 'title';

            case self::MODULE_FORMINPUT_USERNICENAME:
                return 'displayName';

            case self::MODULE_FORMINPUT_TARGETURL:
                return 'url';
        }

        return parent::getDbobjectField($module);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TARGETTITLE:
            case self::MODULE_FORMINPUT_POSTTITLE:
            case self::MODULE_FORMINPUT_USERNICENAME:
                // // fill the input when a new Addon PageSection is created
                // if ($this->getProp($module, $props, 'replicable')) {
                $this->addJsmethod($ret, 'fillAddonInput');
                // }
                break;

            case self::MODULE_FORMINPUT_TARGETURL:
                // // fill the input when a new Addon PageSection is created
                // if ($this->getProp($module, $props, 'replicable')) {
                $this->addJsmethod($ret, 'fillAddonURLInput');
                // }
                break;

            case self::MODULE_FORMINPUT_BROWSERURL:
                $this->addJsmethod($ret, 'browserUrl');
                break;

            case self::MODULE_FORMINPUT_SENDERNAME:
                $this->addJsmethod($ret, 'addDomainClass');
                break;
        }
        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_SENDERNAME:
                // For function addDomainClass
                $ret['addDomainClass']['prefix'] = 'visible-notloggedin-';
                break;
        }

        return $ret;
    }

    public function isHidden(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TARGETURL:
            case self::MODULE_FORMINPUT_TARGETTITLE:
            case self::MODULE_FORMINPUT_POSTTITLE:
            case self::MODULE_FORMINPUT_USERNICENAME:
            case self::MODULE_FORMINPUT_BROWSERURL:
            // case self::MODULE_FORMINPUT_FILTERNAME:
                return true;
        }

        return parent::isHidden($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TARGETURL:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-attr' => 'target-url'
                    )
                );
                break;

            case self::MODULE_FORMINPUT_TARGETTITLE:
            case self::MODULE_FORMINPUT_POSTTITLE:
            case self::MODULE_FORMINPUT_USERNICENAME:
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-attr' => 'target-title'
                    )
                );
                break;

            case self::MODULE_FORMINPUT_BROWSERURL:
                $this->appendProp($module, $props, 'class', 'pop-browserurl');
                break;

            case self::MODULE_FORMINPUT_SENDERNAME:
                $this->appendProp($module, $props, 'class', 'visible-notloggedin');

                // If we don't use the loggedinuser-data, then show the inputs always
                if (!PoP_FormUtils::useLoggedinuserData()) {
                    $this->appendProp($module, $props, 'class', 'visible-always');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



