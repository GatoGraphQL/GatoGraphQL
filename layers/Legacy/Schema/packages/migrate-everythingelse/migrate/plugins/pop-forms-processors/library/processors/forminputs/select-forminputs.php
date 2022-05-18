<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_SelectFilterInputs extends PoP_Module_Processor_SelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_ORDERUSER = 'filterinput-order-user';
    public final const MODULE_FILTERINPUT_ORDERPOST = 'filterinput-order-post';
    public final const MODULE_FILTERINPUT_ORDERTAG = 'filterinput-order-tag';
    public final const MODULE_FILTERINPUT_ORDERCOMMENT = 'filterinput-order-comment';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_ORDERUSER],
            [self::class, self::MODULE_FILTERINPUT_ORDERPOST],
            [self::class, self::MODULE_FILTERINPUT_ORDERTAG],
            [self::class, self::MODULE_FILTERINPUT_ORDERCOMMENT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_ORDERUSER => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERPOST => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERTAG => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_ORDERCOMMENT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_ORDERUSER:
    //         case self::MODULE_FILTERINPUT_ORDERPOST:
    //         case self::MODULE_FILTERINPUT_ORDERTAG:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
            case self::MODULE_FILTERINPUT_ORDERPOST:
            case self::MODULE_FILTERINPUT_ORDERTAG:
            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                return TranslationAPIFacade::getInstance()->__('Order by', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
                return GD_FormInput_OrderUser::class;

            case self::MODULE_FILTERINPUT_ORDERPOST:
                return GD_FormInput_OrderPost::class;

            case self::MODULE_FILTERINPUT_ORDERTAG:
                return GD_FormInput_OrderTag::class;

            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                return GD_FormInput_OrderComment::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDERUSER:
            case self::MODULE_FILTERINPUT_ORDERPOST:
            case self::MODULE_FILTERINPUT_ORDERTAG:
            case self::MODULE_FILTERINPUT_ORDERCOMMENT:
                // Add a nice name, so that the URL params when filtering make sense
                return 'order';
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_ORDERUSER => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_ORDERPOST => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_ORDERTAG => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_ORDERCOMMENT => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_ORDERUSER => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERPOST => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERTAG => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_ORDERCOMMENT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



