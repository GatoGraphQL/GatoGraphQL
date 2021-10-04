<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\ModuleProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ModuleProcessors\AbstractFormInputModuleProcessor;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\FormInputs\BooleanFormInput;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPSchema\SchemaCommons\FormInputs\MultiValueFromStringFormInput;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use Symfony\Contracts\Service\Attribute\Required;

class CommonFilterInputModuleProcessor extends AbstractFormInputModuleProcessor implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_ORDER = 'filterinput-order';
    public const MODULE_FILTERINPUT_LIMIT = 'filterinput-limit';
    public const MODULE_FILTERINPUT_OFFSET = 'filterinput-offset';
    public const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public const MODULE_FILTERINPUT_IDS = 'filterinput-ids';
    public const MODULE_FILTERINPUT_ID = 'filterinput-id';
    public const MODULE_FILTERINPUT_COMMASEPARATED_IDS = 'filterinput-commaseparated-ids';
    public const MODULE_FILTERINPUT_EXCLUDE_IDS = 'filterinput-exclude-ids';
    public const MODULE_FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';
    public const MODULE_FILTERINPUT_PARENT_ID = 'filterinput-parent-id';
    public const MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS = 'filterinput-exclude-parent-ids';
    public const MODULE_FILTERINPUT_SLUGS = 'filterinput-slugs';
    public const MODULE_FILTERINPUT_SLUG = 'filterinput-slug';
    public const MODULE_FILTERINPUT_DATEFORMAT = 'filterinput-date-format';
    public const MODULE_FILTERINPUT_GMT = 'filterinput-date-gmt';

    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireCommonFilterInputModuleProcessor(
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_ORDER],
            [self::class, self::MODULE_FILTERINPUT_LIMIT],
            [self::class, self::MODULE_FILTERINPUT_OFFSET],
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
            [self::class, self::MODULE_FILTERINPUT_IDS],
            [self::class, self::MODULE_FILTERINPUT_ID],
            [self::class, self::MODULE_FILTERINPUT_COMMASEPARATED_IDS],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_IDS],
            [self::class, self::MODULE_FILTERINPUT_PARENT_IDS],
            [self::class, self::MODULE_FILTERINPUT_PARENT_ID],
            [self::class, self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS],
            [self::class, self::MODULE_FILTERINPUT_SLUGS],
            [self::class, self::MODULE_FILTERINPUT_SLUG],
            [self::class, self::MODULE_FILTERINPUT_DATEFORMAT],
            [self::class, self::MODULE_FILTERINPUT_GMT],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_ORDER => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_ORDER],
            self::MODULE_FILTERINPUT_LIMIT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_LIMIT],
            self::MODULE_FILTERINPUT_OFFSET => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_OFFSET],
            self::MODULE_FILTERINPUT_SEARCH => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUT_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            self::MODULE_FILTERINPUT_ID => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_INCLUDE],
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_IDS],
            self::MODULE_FILTERINPUT_PARENT_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_PARENT_IDS],
            self::MODULE_FILTERINPUT_PARENT_ID => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_PARENT_ID],
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_EXCLUDE_PARENT_IDS],
            self::MODULE_FILTERINPUT_SLUGS => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SLUGS],
            self::MODULE_FILTERINPUT_SLUG => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SLUG],
            self::MODULE_FILTERINPUT_DATEFORMAT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_DATEFORMAT],
            self::MODULE_FILTERINPUT_GMT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_GMT],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_ORDER:
                return OrderFormInput::class;
            case self::MODULE_FILTERINPUT_IDS:
            case self::MODULE_FILTERINPUT_EXCLUDE_IDS:
            case self::MODULE_FILTERINPUT_PARENT_IDS:
            case self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS:
            case self::MODULE_FILTERINPUT_SLUGS:
                return FormMultipleInput::class;
            case self::MODULE_FILTERINPUT_COMMASEPARATED_IDS:
                return MultiValueFromStringFormInput::class;
            case self::MODULE_FILTERINPUT_GMT:
                return BooleanFormInput::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_ORDER => 'order',
            self::MODULE_FILTERINPUT_LIMIT => 'limit',
            self::MODULE_FILTERINPUT_OFFSET => 'offset',
            self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
            self::MODULE_FILTERINPUT_IDS => 'ids',
            self::MODULE_FILTERINPUT_ID => 'id',
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => 'id',
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => 'excludeIDs',
            self::MODULE_FILTERINPUT_PARENT_IDS => 'parentIDs',
            self::MODULE_FILTERINPUT_PARENT_ID => 'parentID',
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => 'excludeParentIDs',
            self::MODULE_FILTERINPUT_SLUGS => 'slugs',
            self::MODULE_FILTERINPUT_SLUG => 'slug',
            self::MODULE_FILTERINPUT_DATEFORMAT => 'format',
            self::MODULE_FILTERINPUT_GMT => 'gmt',
            default => parent::getName($module),
        };
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match ((string)$module[1]) {
            self::MODULE_FILTERINPUT_ORDER => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_LIMIT => $this->intScalarTypeResolver,
            self::MODULE_FILTERINPUT_OFFSET => $this->intScalarTypeResolver,
            self::MODULE_FILTERINPUT_SEARCH => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_IDS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_ID => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_PARENT_IDS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_PARENT_ID => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_SLUGS => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_SLUG => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_DATEFORMAT => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_GMT => $this->booleanScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_IDS,
            self::MODULE_FILTERINPUT_PARENT_IDS,
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS,
            self::MODULE_FILTERINPUT_SLUGS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        return match ((string)$module[1]) {
            self::MODULE_FILTERINPUT_ORDER => $this->translationAPI->__('Order the results. Specify the \'orderby\' and \'order\' (\'ASC\' or \'DESC\') fields in this format: \'orderby|order\'', 'schema-commons'),
            self::MODULE_FILTERINPUT_LIMIT => $this->translationAPI->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'schema-commons'),
            self::MODULE_FILTERINPUT_OFFSET => $this->translationAPI->__('Offset the results by how many places (required for pagination)', 'schema-commons'),
            self::MODULE_FILTERINPUT_SEARCH => $this->translationAPI->__('Search for elements containing the given string', 'schema-commons'),
            self::MODULE_FILTERINPUT_IDS => $this->translationAPI->__('Limit results to elements with the given IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_ID => $this->translationAPI->__('Fetch the element with the given ID', 'schema-commons'),
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => sprintf(
                $this->translationAPI->__('Limit results to elements with the given ID, or IDs (separated by \'%s\')', 'schema-commons'),
                Param::VALUE_SEPARATOR
            ),
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => $this->translationAPI->__('Exclude elements with the given IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_PARENT_IDS => $this->translationAPI->__('Limit results to elements with the given parent IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_PARENT_ID => $this->translationAPI->__('Limit results to elements with the given parent ID', 'schema-commons'),
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->translationAPI->__('Exclude elements with the given parent IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_SLUGS => $this->translationAPI->__('Limit results to elements with the given slug', 'schema-commons'),
            self::MODULE_FILTERINPUT_SLUGS => $this->translationAPI->__('Limit results to elements with the given slug', 'schema-commons'),
            self::MODULE_FILTERINPUT_DATEFORMAT => sprintf(
                $this->translationAPI->__('Date format, as defined in %s', 'schema-commons'),
                'https://www.php.net/manual/en/function.date.php'
            ),
            self::MODULE_FILTERINPUT_GMT => $this->translationAPI->__('Whether to retrieve the date as UTC or GMT timezone', 'schema-commons'),
            default => null,
        };
    }
}
