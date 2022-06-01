<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\FormInputs\BooleanFormInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\FormatFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\GMTFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\IncludeFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\LimitFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\OffsetFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SortFilterInput;
use PoPCMSSchema\SchemaCommons\FormInputs\MultiValueFromStringFormInput;
use PoPCMSSchema\SchemaCommons\FormInputs\OrderFormInput;

class CommonFilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public final const COMPONENT_FILTERINPUT_SORT = 'filterinput-sort';
    public final const COMPONENT_FILTERINPUT_LIMIT = 'filterinput-limit';
    public final const COMPONENT_FILTERINPUT_OFFSET = 'filterinput-offset';
    public final const COMPONENT_FILTERINPUT_SEARCH = 'filterinput-search';
    public final const COMPONENT_FILTERINPUT_IDS = 'filterinput-ids';
    public final const COMPONENT_FILTERINPUT_ID = 'filterinput-id';
    public final const COMPONENT_FILTERINPUT_COMMASEPARATED_IDS = 'filterinput-commaseparated-ids';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_IDS = 'filterinput-exclude-ids';
    public final const COMPONENT_FILTERINPUT_PARENT_IDS = 'filterinput-parent-ids';
    public final const COMPONENT_FILTERINPUT_PARENT_ID = 'filterinput-parent-id';
    public final const COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS = 'filterinput-exclude-parent-ids';
    public final const COMPONENT_FILTERINPUT_SLUGS = 'filterinput-slugs';
    public final const COMPONENT_FILTERINPUT_SLUG = 'filterinput-slug';
    public final const COMPONENT_FILTERINPUT_DATEFORMAT = 'filterinput-date-format';
    public final const COMPONENT_FILTERINPUT_GMT = 'filterinput-date-gmt';

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SortFilterInput $sortFilterInput = null;
    private ?ExcludeIDsFilterInput $excludeIDsFilterInput = null;
    private ?ExcludeParentIDsFilterInput $excludeParentIDsFilterInput = null;
    private ?FormatFilterInput $formatFilterInput = null;
    private ?GMTFilterInput $gmtFilterInput = null;
    private ?IncludeFilterInput $includeFilterInput = null;
    private ?LimitFilterInput $limitFilterInput = null;
    private ?OffsetFilterInput $offsetFilterInput = null;
    private ?ParentIDFilterInput $parentIDFilterInput = null;
    private ?ParentIDsFilterInput $parentIDsFilterInput = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?SlugFilterInput $slugFilterInput = null;
    private ?SlugsFilterInput $slugsFilterInput = null;

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
    final public function setSortFilterInput(SortFilterInput $sortFilterInput): void
    {
        $this->sortFilterInput = $sortFilterInput;
    }
    final protected function getSortFilterInput(): SortFilterInput
    {
        return $this->sortFilterInput ??= $this->instanceManager->getInstance(SortFilterInput::class);
    }
    final public function setExcludeIDsFilterInput(ExcludeIDsFilterInput $excludeIDsFilterInput): void
    {
        $this->excludeIDsFilterInput = $excludeIDsFilterInput;
    }
    final protected function getExcludeIDsFilterInput(): ExcludeIDsFilterInput
    {
        return $this->excludeIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeIDsFilterInput::class);
    }
    final public function setExcludeParentIDsFilterInput(ExcludeParentIDsFilterInput $excludeParentIDsFilterInput): void
    {
        $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
    }
    final protected function getExcludeParentIDsFilterInput(): ExcludeParentIDsFilterInput
    {
        return $this->excludeParentIDsFilterInput ??= $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
    }
    final public function setFormatFilterInput(FormatFilterInput $formatFilterInput): void
    {
        $this->formatFilterInput = $formatFilterInput;
    }
    final protected function getFormatFilterInput(): FormatFilterInput
    {
        return $this->formatFilterInput ??= $this->instanceManager->getInstance(FormatFilterInput::class);
    }
    final public function setGMTFilterInput(GMTFilterInput $gmtFilterInput): void
    {
        $this->gmtFilterInput = $gmtFilterInput;
    }
    final protected function getGMTFilterInput(): GMTFilterInput
    {
        return $this->gmtFilterInput ??= $this->instanceManager->getInstance(GMTFilterInput::class);
    }
    final public function setIncludeFilterInput(IncludeFilterInput $includeFilterInput): void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        return $this->includeFilterInput ??= $this->instanceManager->getInstance(IncludeFilterInput::class);
    }
    final public function setLimitFilterInput(LimitFilterInput $limitFilterInput): void
    {
        $this->limitFilterInput = $limitFilterInput;
    }
    final protected function getLimitFilterInput(): LimitFilterInput
    {
        return $this->limitFilterInput ??= $this->instanceManager->getInstance(LimitFilterInput::class);
    }
    final public function setOffsetFilterInput(OffsetFilterInput $offsetFilterInput): void
    {
        $this->offsetFilterInput = $offsetFilterInput;
    }
    final protected function getOffsetFilterInput(): OffsetFilterInput
    {
        return $this->offsetFilterInput ??= $this->instanceManager->getInstance(OffsetFilterInput::class);
    }
    final public function setParentIDFilterInput(ParentIDFilterInput $parentIDFilterInput): void
    {
        $this->parentIDFilterInput = $parentIDFilterInput;
    }
    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        return $this->parentIDFilterInput ??= $this->instanceManager->getInstance(ParentIDFilterInput::class);
    }
    final public function setParentIDsFilterInput(ParentIDsFilterInput $parentIDsFilterInput): void
    {
        $this->parentIDsFilterInput = $parentIDsFilterInput;
    }
    final protected function getParentIDsFilterInput(): ParentIDsFilterInput
    {
        return $this->parentIDsFilterInput ??= $this->instanceManager->getInstance(ParentIDsFilterInput::class);
    }
    final public function setSearchFilterInput(SearchFilterInput $searchFilterInput): void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        return $this->searchFilterInput ??= $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    final public function setSlugFilterInput(SlugFilterInput $slugFilterInput): void
    {
        $this->slugFilterInput = $slugFilterInput;
    }
    final protected function getSlugFilterInput(): SlugFilterInput
    {
        return $this->slugFilterInput ??= $this->instanceManager->getInstance(SlugFilterInput::class);
    }
    final public function setSlugsFilterInput(SlugsFilterInput $slugsFilterInput): void
    {
        $this->slugsFilterInput = $slugsFilterInput;
    }
    final protected function getSlugsFilterInput(): SlugsFilterInput
    {
        return $this->slugsFilterInput ??= $this->instanceManager->getInstance(SlugsFilterInput::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_SORT],
            [self::class, self::COMPONENT_FILTERINPUT_LIMIT],
            [self::class, self::COMPONENT_FILTERINPUT_OFFSET],
            [self::class, self::COMPONENT_FILTERINPUT_SEARCH],
            [self::class, self::COMPONENT_FILTERINPUT_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_ID],
            [self::class, self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_PARENT_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_PARENT_ID],
            [self::class, self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS],
            [self::class, self::COMPONENT_FILTERINPUT_SLUGS],
            [self::class, self::COMPONENT_FILTERINPUT_SLUG],
            [self::class, self::COMPONENT_FILTERINPUT_DATEFORMAT],
            [self::class, self::COMPONENT_FILTERINPUT_GMT],
        );
    }

    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_SORT => $this->getSortFilterInput(),
            self::COMPONENT_FILTERINPUT_LIMIT => $this->getLimitFilterInput(),
            self::COMPONENT_FILTERINPUT_OFFSET => $this->getOffsetFilterInput(),
            self::COMPONENT_FILTERINPUT_SEARCH => $this->getSearchFilterInput(),
            self::COMPONENT_FILTERINPUT_IDS => $this->getIncludeFilterInput(),
            self::COMPONENT_FILTERINPUT_ID => $this->getIncludeFilterInput(),
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS => $this->getIncludeFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS => $this->getExcludeIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_PARENT_IDS => $this->getParentIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_PARENT_ID => $this->getParentIDFilterInput(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->getExcludeParentIDsFilterInput(),
            self::COMPONENT_FILTERINPUT_SLUGS => $this->getSlugsFilterInput(),
            self::COMPONENT_FILTERINPUT_SLUG => $this->getSlugFilterInput(),
            self::COMPONENT_FILTERINPUT_DATEFORMAT => $this->getFormatFilterInput(),
            self::COMPONENT_FILTERINPUT_GMT => $this->getGMTFilterInput(),
            default => null,
        };
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_SORT:
                return OrderFormInput::class;
            case self::COMPONENT_FILTERINPUT_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_IDS:
            case self::COMPONENT_FILTERINPUT_PARENT_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS:
            case self::COMPONENT_FILTERINPUT_SLUGS:
                return FormMultipleInput::class;
            case self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS:
                return MultiValueFromStringFormInput::class;
            case self::COMPONENT_FILTERINPUT_GMT:
                return BooleanFormInput::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ((string) $component->name) {
            self::COMPONENT_FILTERINPUT_SORT => 'order',
            self::COMPONENT_FILTERINPUT_LIMIT => 'limit',
            self::COMPONENT_FILTERINPUT_OFFSET => 'offset',
            self::COMPONENT_FILTERINPUT_SEARCH => 'searchfor',
            self::COMPONENT_FILTERINPUT_IDS => 'ids',
            self::COMPONENT_FILTERINPUT_ID => 'id',
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS => 'id',
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS => 'excludeIDs',
            self::COMPONENT_FILTERINPUT_PARENT_IDS => 'parentIDs',
            self::COMPONENT_FILTERINPUT_PARENT_ID => 'parentID',
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS => 'excludeParentIDs',
            self::COMPONENT_FILTERINPUT_SLUGS => 'slugs',
            self::COMPONENT_FILTERINPUT_SLUG => 'slug',
            self::COMPONENT_FILTERINPUT_DATEFORMAT => 'format',
            self::COMPONENT_FILTERINPUT_GMT => 'gmt',
            default => parent::getName($component),
        };
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match ((string)$component->name) {
            self::COMPONENT_FILTERINPUT_SORT => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_LIMIT => $this->getIntScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_OFFSET => $this->getIntScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_SEARCH => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_ID => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_PARENT_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_PARENT_ID => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->getIDScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_SLUGS => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_SLUG => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_DATEFORMAT => $this->getStringScalarTypeResolver(),
            self::COMPONENT_FILTERINPUT_GMT => $this->getBooleanScalarTypeResolver(),
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int
    {
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS,
            self::COMPONENT_FILTERINPUT_PARENT_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS,
            self::COMPONENT_FILTERINPUT_SLUGS
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        return match ((string)$component->name) {
            self::COMPONENT_FILTERINPUT_SORT => $this->__('Order the results. Specify the \'orderby\' and \'order\' (\'ASC\' or \'DESC\') fields in this format: \'orderby|order\'', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_LIMIT => $this->__('Limit the results. \'-1\' brings all the results (or the maximum amount allowed)', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_OFFSET => $this->__('Offset the results by how many positions', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_SEARCH => $this->__('Search for elements containing the given string', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_IDS => $this->__('Limit results to elements with the given IDs', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_ID => $this->__('Fetch the element with the given ID', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS => sprintf(
                $this->__('Limit results to elements with the given ID, or IDs (separated by \'%s\')', 'schema-commons'),
                Param::VALUE_SEPARATOR
            ),
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS => $this->__('Exclude elements with the given IDs', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_PARENT_IDS => $this->__('Limit results to elements with the given parent IDs. \'0\' means \'no parent\'', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_PARENT_ID => $this->__('Limit results to elements with the given parent ID. \'0\' means \'no parent\'', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->__('Exclude elements with the given parent IDs', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_SLUGS => $this->__('Limit results to elements with the given slug', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_SLUGS => $this->__('Limit results to elements with the given slug', 'schema-commons'),
            self::COMPONENT_FILTERINPUT_DATEFORMAT => sprintf(
                $this->__('Date format, as defined in %s', 'schema-commons'),
                'https://www.php.net/manual/en/function.date.php'
            ),
            self::COMPONENT_FILTERINPUT_GMT => $this->__('Whether to retrieve the date as UTC or GMT timezone', 'schema-commons'),
            default => null,
        };
    }
}
