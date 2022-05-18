<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs;

use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\FormInputs\BooleanFormInput;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FormInputs\MultiValueFromStringFormInput;
use PoPCMSSchema\SchemaCommons\FormInputs\OrderFormInput;

class CommonFilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const MODULE_FILTERINPUT_SORT = 'filterinput-sort';
    public final const MODULE_FILTERINPUT_LIMIT = 'filterinput-limit';
    public final const MODULE_FILTERINPUT_OFFSET = 'filterinput-offset';
    public final const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public final const MODULE_FILTERINPUT_IDS = 'filterinput-ids';
    public final const MODULE_FILTERINPUT_ID = 'filterinput-id';
    public final const MODULE_FILTERINPUT_COMMASEPARATED_IDS = 'filterinput-commaseparated-ids';
    public final const MODULE_FILTERINPUT_EXCLUDE_IDS = 'filterinput-exclude-ids';
    public final const MODULE_FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';
    public final const MODULE_FILTERINPUT_PARENT_ID = 'filterinput-parent-id';
    public final const MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS = 'filterinput-exclude-parent-ids';
    public final const MODULE_FILTERINPUT_SLUGS = 'filterinput-slugs';
    public final const MODULE_FILTERINPUT_SLUG = 'filterinput-slug';
    public final const MODULE_FILTERINPUT_DATEFORMAT = 'filterinput-date-format';
    public final const MODULE_FILTERINPUT_GMT = 'filterinput-date-gmt';

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_SORT],
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

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_SORT => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SORT],
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
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_SORT:
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

        return parent::getInputClass($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ((string) $componentVariation[1]) {
            self::MODULE_FILTERINPUT_SORT => 'order',
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
            default => parent::getName($componentVariation),
        };
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match ((string)$componentVariation[1]) {
            self::MODULE_FILTERINPUT_SORT => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_LIMIT => $this->getIntScalarTypeResolver(),
            self::MODULE_FILTERINPUT_OFFSET => $this->getIntScalarTypeResolver(),
            self::MODULE_FILTERINPUT_SEARCH => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_ID => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_PARENT_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_PARENT_ID => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->getIDScalarTypeResolver(),
            self::MODULE_FILTERINPUT_SLUGS => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_SLUG => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_DATEFORMAT => $this->getStringScalarTypeResolver(),
            self::MODULE_FILTERINPUT_GMT => $this->getBooleanScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match ($componentVariation[1]) {
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

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        return match ((string)$componentVariation[1]) {
            self::MODULE_FILTERINPUT_SORT => $this->__('Order the results. Specify the \'orderby\' and \'order\' (\'ASC\' or \'DESC\') fields in this format: \'orderby|order\'', 'schema-commons'),
            self::MODULE_FILTERINPUT_LIMIT => $this->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'schema-commons'),
            self::MODULE_FILTERINPUT_OFFSET => $this->__('Offset the results by how many positions', 'schema-commons'),
            self::MODULE_FILTERINPUT_SEARCH => $this->__('Search for elements containing the given string', 'schema-commons'),
            self::MODULE_FILTERINPUT_IDS => $this->__('Limit results to elements with the given IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_ID => $this->__('Fetch the element with the given ID', 'schema-commons'),
            self::MODULE_FILTERINPUT_COMMASEPARATED_IDS => sprintf(
                $this->__('Limit results to elements with the given ID, or IDs (separated by \'%s\')', 'schema-commons'),
                Param::VALUE_SEPARATOR
            ),
            self::MODULE_FILTERINPUT_EXCLUDE_IDS => $this->__('Exclude elements with the given IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_PARENT_IDS => $this->__('Limit results to elements with the given parent IDs. \'0\' means \'no parent\'', 'schema-commons'),
            self::MODULE_FILTERINPUT_PARENT_ID => $this->__('Limit results to elements with the given parent ID. \'0\' means \'no parent\'', 'schema-commons'),
            self::MODULE_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->__('Exclude elements with the given parent IDs', 'schema-commons'),
            self::MODULE_FILTERINPUT_SLUGS => $this->__('Limit results to elements with the given slug', 'schema-commons'),
            self::MODULE_FILTERINPUT_SLUGS => $this->__('Limit results to elements with the given slug', 'schema-commons'),
            self::MODULE_FILTERINPUT_DATEFORMAT => sprintf(
                $this->__('Date format, as defined in %s', 'schema-commons'),
                'https://www.php.net/manual/en/function.date.php'
            ),
            self::MODULE_FILTERINPUT_GMT => $this->__('Whether to retrieve the date as UTC or GMT timezone', 'schema-commons'),
            default => null,
        };
    }
}
