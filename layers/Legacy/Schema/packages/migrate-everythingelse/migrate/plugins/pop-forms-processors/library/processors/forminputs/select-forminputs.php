<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FilterInputs\OrderFilterInput;

class PoP_Module_Processor_SelectFilterInputs extends PoP_Module_Processor_SelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_ORDERUSER = 'filterinput-order-user';
    public final const COMPONENT_FILTERINPUT_ORDERPOST = 'filterinput-order-post';
    public final const COMPONENT_FILTERINPUT_ORDERTAG = 'filterinput-order-tag';
    public final const COMPONENT_FILTERINPUT_ORDERCOMMENT = 'filterinput-order-comment';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?OrderFilterInput $orderFilterInput = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setOrderFilterInput(OrderFilterInput $orderFilterInput): void
    {
        $this->orderFilterInput = $orderFilterInput;
    }
    final protected function getOrderFilterInput(): OrderFilterInput
    {
        return $this->orderFilterInput ??= $this->instanceManager->getInstance(OrderFilterInput::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_ORDERUSER],
            [self::class, self::COMPONENT_FILTERINPUT_ORDERPOST],
            [self::class, self::COMPONENT_FILTERINPUT_ORDERTAG],
            [self::class, self::COMPONENT_FILTERINPUT_ORDERCOMMENT],
        );
    }

    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_ORDERUSER => $this->getOrderFilterInput(),
            self::COMPONENT_FILTERINPUT_ORDERPOST => $this->getOrderFilterInput(),
            self::COMPONENT_FILTERINPUT_ORDERTAG => $this->getOrderFilterInput(),
            self::COMPONENT_FILTERINPUT_ORDERCOMMENT => $this->getOrderFilterInput(),
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_ORDERUSER:
    //         case self::COMPONENT_FILTERINPUT_ORDERPOST:
    //         case self::COMPONENT_FILTERINPUT_ORDERTAG:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_ORDERUSER:
            case self::COMPONENT_FILTERINPUT_ORDERPOST:
            case self::COMPONENT_FILTERINPUT_ORDERTAG:
            case self::COMPONENT_FILTERINPUT_ORDERCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Order by', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_ORDERUSER:
                return GD_FormInput_OrderUser::class;

            case self::COMPONENT_FILTERINPUT_ORDERPOST:
                return GD_FormInput_OrderPost::class;

            case self::COMPONENT_FILTERINPUT_ORDERTAG:
                return GD_FormInput_OrderTag::class;

            case self::COMPONENT_FILTERINPUT_ORDERCOMMENT:
                return GD_FormInput_OrderComment::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_ORDERUSER:
            case self::COMPONENT_FILTERINPUT_ORDERPOST:
            case self::COMPONENT_FILTERINPUT_ORDERTAG:
            case self::COMPONENT_FILTERINPUT_ORDERCOMMENT:
                // Add a nice name, so that the URL params when filtering make sense
                return 'order';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_ORDERUSER => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_ORDERPOST => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_ORDERTAG => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_ORDERCOMMENT => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_ORDERUSER => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_ORDERPOST => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_ORDERTAG => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_ORDERCOMMENT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



