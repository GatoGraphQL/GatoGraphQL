<?php
use PoP\Engine\Route\RouteUtils;

class PoP_Newsletter_Module_Processor_Blocks extends PoP_Module_Processor_FormBlocksBase
{
    public final const COMPONENT_BLOCK_NEWSLETTER = 'block-newsletter';
    public final const COMPONENT_BLOCKCODE_NEWSLETTER = 'blockcode-newsletter';
    public final const COMPONENT_BLOCK_NEWSLETTERUNSUBSCRIPTION = 'block-newsletterunsubscription';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BLOCK_NEWSLETTER],
            [self::class, self::COMPONENT_BLOCKCODE_NEWSLETTER],
            [self::class, self::COMPONENT_BLOCK_NEWSLETTERUNSUBSCRIPTION],
        );
    }

    public function getRelevantRoute(array $component, array &$props): ?string
    {
        return match($component[1]) {
            self::COMPONENT_BLOCK_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::COMPONENT_BLOCKCODE_NEWSLETTER => POP_NEWSLETTER_ROUTE_NEWSLETTER,
            self::COMPONENT_BLOCK_NEWSLETTERUNSUBSCRIPTION => POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION,
            default => parent::getRelevantRoute($component, $props),
        };
    }

    public function getTitle(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_NEWSLETTER:
                return '';
        }

        return parent::getTitle($component, $props);
    }

    protected function getInnerSubcomponents(array $component): array
    {
        $ret = parent::getInnerSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_NEWSLETTER:
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::COMPONENT_DATALOAD_NEWSLETTER];
                break;

            case self::COMPONENT_BLOCKCODE_NEWSLETTER:
                $ret[] = [GenericForms_Module_Processor_PageCodes::class, GenericForms_Module_Processor_PageCodes::COMPONENT_PAGECODE_NEWSLETTER];
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::COMPONENT_DATALOAD_NEWSLETTER];
                break;

            case self::COMPONENT_BLOCK_NEWSLETTERUNSUBSCRIPTION:
                $ret[] = [PoP_Newsletter_Module_Processor_Dataloads::class, PoP_Newsletter_Module_Processor_Dataloads::COMPONENT_DATALOAD_NEWSLETTERUNSUBSCRIPTION];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $newsletter_description = \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_GFBlocks:newsletter:description',
            ''
        );
        switch ($component[1]) {
            case self::COMPONENT_BLOCK_NEWSLETTER:
                $this->appendProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER], $props, 'class', 'form-inline');
                if ($newsletter_description) {
                    $title_tag = \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_GFBlocks:newsletter:titletag',
                        'h3'
                    );
                    $description = sprintf(
                        '<%1$s>%2$s</%1$s>',
                        $title_tag,
                        $newsletter_description
                    );
                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER], $props, 'description', $description);
                }

                if ($description_bottom = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_GFBlocks:newsletter:descriptionbottom',
                    ''
                )
                ) {
                    $description_bottom = sprintf(
                        '<p><em><a href="%s" class="text-info">%s</a></em></p>',
                        RouteUtils::getRouteURL(POP_NEWSLETTER_ROUTE_NEWSLETTER),
                        $description_bottom
                    );

                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER], $props, 'description-bottom', $description_bottom);
                }
                break;

            case self::COMPONENT_BLOCKCODE_NEWSLETTER:
                $this->appendProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER], $props, 'class', 'alert alert-info');

                if ($newsletter_description) {
                    $description = sprintf(
                        '<p>%s</p>',
                        $newsletter_description
                    );
                    $this->setProp([PoP_Newsletter_Module_Processor_GFForms::class, PoP_Newsletter_Module_Processor_GFForms::COMPONENT_FORM_NEWSLETTER], $props, 'description', $description);
                }
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_BLOCK_NEWSLETTER:
            case self::COMPONENT_BLOCKCODE_NEWSLETTER:
                $this->appendProp($component, $props, 'class', 'block-newsletter');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



