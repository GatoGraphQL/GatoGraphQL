<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Newsletter_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public const MODULE_BLOCK_NEWSLETTER = 'block-newsletter';
    public const MODULE_BLOCKCODE_NEWSLETTER = 'blockcode-newsletter';
    public const MODULE_BLOCK_NEWSLETTERUNSUBSCRIPTION = 'block-newsletterunsubscription';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_NEWSLETTER],
            [self::class, self::MODULE_BLOCKCODE_NEWSLETTER],
            [self::class, self::MODULE_BLOCK_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getRelevantRoute(array $module, array &$props): ?string
    {
        return match($module[1]) {
            self::MODULE_BLOCK_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::MODULE_BLOCKCODE_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::MODULE_BLOCK_NEWSLETTERUNSUBSCRIPTION => POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            default => parent::getRelevantRoute($module, $props),
        };
    }

    public function getTitle(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BLOCK_NEWSLETTER:
                return '';
        }

        return parent::getTitle($module, $props);
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BLOCK_NEWSLETTER:
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::MODULE_DATALOAD_NEWSLETTER];
                break;

            case self::MODULE_BLOCKCODE_NEWSLETTER:
                $ret[] = [GenericForms_Module_Processor_PageCodes::class, GenericForms_Module_Processor_PageCodes::MODULE_PAGECODE_NEWSLETTER];
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::MODULE_DATALOAD_NEWSLETTER];
                break;

            case self::MODULE_BLOCK_NEWSLETTERUNSUBSCRIPTION:
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::MODULE_DATALOAD_NEWSLETTERUNSUBSCRIPTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $newsletter_description = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Module_Processor_GFBlocks:newsletter:description',
            ''
        );
        switch ($module[1]) {
            case self::MODULE_BLOCK_NEWSLETTER:
                $this->appendProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER], $props, 'class', 'form-inline');
                if ($newsletter_description) {
                    $title_tag = HooksAPIFacade::getInstance()->applyFilters(
                        'PoP_Module_Processor_GFBlocks:newsletter:titletag',
                        'h3'
                    );
                    $description = sprintf(
                        '<%1$s>%2$s</%1$s>',
                        $title_tag,
                        $newsletter_description
                    );
                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER], $props, 'description', $description);
                }

                if ($description_bottom = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_GFBlocks:newsletter:descriptionbottom',
                    ''
                )
                ) {
                    $description_bottom = sprintf(
                        '<p><em><a href="%s" class="text-info">%s</a></em></p>',
                        RouteUtils::getRouteURL(POP_NEWSLETTER_ROUTE_NEWSLETTER),
                        $description_bottom
                    );

                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER], $props, 'description-bottom', $description_bottom);
                }
                break;

            case self::MODULE_BLOCKCODE_NEWSLETTER:
                $this->appendProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER], $props, 'class', 'alert alert-info');

                if ($newsletter_description) {
                    $description = sprintf(
                        '<p>%s</p>',
                        $newsletter_description
                    );
                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::MODULE_FORM_NEWSLETTER], $props, 'description', $description);
                }
                break;
        }

        switch ($module[1]) {
            case self::MODULE_BLOCK_NEWSLETTER:
            case self::MODULE_BLOCKCODE_NEWSLETTER:
                $this->appendProp($module, $props, 'class', 'block-newsletter');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



