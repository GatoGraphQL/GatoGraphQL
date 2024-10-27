<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;

class CommonFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';

    public final const COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID = 'filterinputcontainer-entity-by-id';
    public final const COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG = 'filterinputcontainer-entity-by-slug';
    public final const COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING = 'filterinputcontainer-date-as-string';
    public final const COMPONENT_FILTERINPUTCONTAINER_GMTDATE = 'filterinputcontainer-utcdate';
    public final const COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING = 'filterinputcontainer-utcdate-as-string';

    private ?CMSServiceInterface $cmsService = null;

    final protected function getCMSService(): CMSServiceInterface
    {
        if ($this->cmsService === null) {
            /** @var CMSServiceInterface */
            $cmsService = $this->instanceManager->getInstance(CMSServiceInterface::class);
            $this->cmsService = $cmsService;
        }
        return $this->cmsService;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID,
            self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG,
            self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING,
            self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE,
            self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING,
        );
    }

    /**
     * @return Component[]
     */
    public function getFilterInputComponents(Component $component): array
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID => [
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG => [
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING => [
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE => [
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT),
            ],
            self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING => [
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT),
                new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT),
            ],
            default => [],
        };
    }

    public function getFieldFilterInputDefaultValue(Component $component, string $fieldArgName): mixed
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_DATE_AS_STRING:
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $formatFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_DATEFORMAT
                ));
                if ($fieldArgName === $formatFilterInputName) {
                    return $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:dateFormat'));
                }
                break;
        }
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE:
            case self::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING:
                $gmtFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_GMT
                ));
                if ($fieldArgName === $gmtFilterInputName) {
                    return false;
                }
                break;
        }
        return parent::getFieldFilterInputDefaultValue($component, $fieldArgName);
    }

    public function getFieldFilterInputTypeModifiers(Component $component, string $fieldArgName): int
    {
        $fieldFilterInputTypeModifiers = parent::getFieldFilterInputTypeModifiers($component, $fieldArgName);
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_ID:
                $idFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_ID
                ));
                if ($fieldArgName === $idFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
            case self::COMPONENT_FILTERINPUTCONTAINER_ENTITY_BY_SLUG:
                $slugFilterInputName = FilterInputHelper::getFilterInputName(new Component(
                    CommonFilterInputComponentProcessor::class,
                    CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SLUG
                ));
                if ($fieldArgName === $slugFilterInputName) {
                    return $fieldFilterInputTypeModifiers | SchemaTypeModifiers::MANDATORY;
                }
                break;
        }
        return $fieldFilterInputTypeModifiers;
    }

    /**
     * @return string[]
     */
    protected function getFilterInputHookNames(): array
    {
        return [
            ...parent::getFilterInputHookNames(),
            self::HOOK_FILTER_INPUTS,
        ];
    }
}
