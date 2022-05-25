<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs;

use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\FormInputs\BooleanFormInput;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ExcludeIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ExcludeParentIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FormatFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\GMTFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\IncludeFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\LimitFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\OffsetFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\ParentIDsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SlugFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SlugsFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SortFilterInputProcessor;
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
    private ?SortFilterInputProcessor $sortFilterInputProcessor = null;
    private ?ExcludeIDsFilterInputProcessor $excludeIDsFilterInputProcessor = null;
    private ?ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor = null;
    private ?FormatFilterInputProcessor $formatFilterInputProcessor = null;
    private ?GMTFilterInputProcessor $gmtFilterInputProcessor = null;
    private ?IncludeFilterInputProcessor $includeFilterInputProcessor = null;
    private ?LimitFilterInputProcessor $limitFilterInputProcessor = null;
    private ?OffsetFilterInputProcessor $offsetFilterInputProcessor = null;
    private ?ParentIDFilterInputProcessor $parentIDFilterInputProcessor = null;
    private ?ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor = null;
    private ?SearchFilterInputProcessor $searchFilterInputProcessor = null;
    private ?SlugFilterInputProcessor $slugFilterInputProcessor = null;
    private ?SlugsFilterInputProcessor $slugsFilterInputProcessor = null;

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
    final public function setSortFilterInputProcessor(SortFilterInputProcessor $sortFilterInputProcessor): void
    {
        $this->sortFilterInputProcessor = $sortFilterInputProcessor;
    }
    final protected function getSortFilterInputProcessor(): SortFilterInputProcessor
    {
        return $this->sortFilterInputProcessor ??= $this->instanceManager->getInstance(SortFilterInputProcessor::class);
    }
    final public function setExcludeIDsFilterInputProcessor(ExcludeIDsFilterInputProcessor $excludeIDsFilterInputProcessor): void
    {
        $this->excludeIDsFilterInputProcessor = $excludeIDsFilterInputProcessor;
    }
    final protected function getExcludeIDsFilterInputProcessor(): ExcludeIDsFilterInputProcessor
    {
        return $this->excludeIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeIDsFilterInputProcessor::class);
    }
    final public function setExcludeParentIDsFilterInputProcessor(ExcludeParentIDsFilterInputProcessor $excludeParentIDsFilterInputProcessor): void
    {
        $this->excludeParentIDsFilterInputProcessor = $excludeParentIDsFilterInputProcessor;
    }
    final protected function getExcludeParentIDsFilterInputProcessor(): ExcludeParentIDsFilterInputProcessor
    {
        return $this->excludeParentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ExcludeParentIDsFilterInputProcessor::class);
    }
    final public function setFormatFilterInputProcessor(FormatFilterInputProcessor $formatFilterInputProcessor): void
    {
        $this->formatFilterInputProcessor = $formatFilterInputProcessor;
    }
    final protected function getFormatFilterInputProcessor(): FormatFilterInputProcessor
    {
        return $this->formatFilterInputProcessor ??= $this->instanceManager->getInstance(FormatFilterInputProcessor::class);
    }
    final public function setGMTFilterInputProcessor(GMTFilterInputProcessor $gmtFilterInputProcessor): void
    {
        $this->gmtFilterInputProcessor = $gmtFilterInputProcessor;
    }
    final protected function getGMTFilterInputProcessor(): GMTFilterInputProcessor
    {
        return $this->gmtFilterInputProcessor ??= $this->instanceManager->getInstance(GMTFilterInputProcessor::class);
    }
    final public function setIncludeFilterInputProcessor(IncludeFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getIncludeFilterInputProcessor(): IncludeFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(IncludeFilterInputProcessor::class);
    }
    final public function setLimitFilterInputProcessor(LimitFilterInputProcessor $limitFilterInputProcessor): void
    {
        $this->limitFilterInputProcessor = $limitFilterInputProcessor;
    }
    final protected function getLimitFilterInputProcessor(): LimitFilterInputProcessor
    {
        return $this->limitFilterInputProcessor ??= $this->instanceManager->getInstance(LimitFilterInputProcessor::class);
    }
    final public function setOffsetFilterInputProcessor(OffsetFilterInputProcessor $offsetFilterInputProcessor): void
    {
        $this->offsetFilterInputProcessor = $offsetFilterInputProcessor;
    }
    final protected function getOffsetFilterInputProcessor(): OffsetFilterInputProcessor
    {
        return $this->offsetFilterInputProcessor ??= $this->instanceManager->getInstance(OffsetFilterInputProcessor::class);
    }
    final public function setParentIDFilterInputProcessor(ParentIDFilterInputProcessor $parentIDFilterInputProcessor): void
    {
        $this->parentIDFilterInputProcessor = $parentIDFilterInputProcessor;
    }
    final protected function getParentIDFilterInputProcessor(): ParentIDFilterInputProcessor
    {
        return $this->parentIDFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDFilterInputProcessor::class);
    }
    final public function setParentIDsFilterInputProcessor(ParentIDsFilterInputProcessor $parentIDsFilterInputProcessor): void
    {
        $this->parentIDsFilterInputProcessor = $parentIDsFilterInputProcessor;
    }
    final protected function getParentIDsFilterInputProcessor(): ParentIDsFilterInputProcessor
    {
        return $this->parentIDsFilterInputProcessor ??= $this->instanceManager->getInstance(ParentIDsFilterInputProcessor::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $searchFilterInputProcessor): void
    {
        $this->searchFilterInputProcessor = $searchFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->searchFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }
    final public function setSlugFilterInputProcessor(SlugFilterInputProcessor $slugFilterInputProcessor): void
    {
        $this->slugFilterInputProcessor = $slugFilterInputProcessor;
    }
    final protected function getSlugFilterInputProcessor(): SlugFilterInputProcessor
    {
        return $this->slugFilterInputProcessor ??= $this->instanceManager->getInstance(SlugFilterInputProcessor::class);
    }
    final public function setSlugsFilterInputProcessor(SlugsFilterInputProcessor $slugsFilterInputProcessor): void
    {
        $this->slugsFilterInputProcessor = $slugsFilterInputProcessor;
    }
    final protected function getSlugsFilterInputProcessor(): SlugsFilterInputProcessor
    {
        return $this->slugsFilterInputProcessor ??= $this->instanceManager->getInstance(SlugsFilterInputProcessor::class);
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

    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_SORT => $this->getSortFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_LIMIT => $this->getLimitFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_OFFSET => $this->getOffsetFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_SEARCH => $this->getSearchFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_IDS => $this->getIncludeFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_ID => $this->getIncludeFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS => $this->getIncludeFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS => $this->getExcludeIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_PARENT_IDS => $this->getParentIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_PARENT_ID => $this->getParentIDFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS => $this->getExcludeParentIDsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_SLUGS => $this->getSlugsFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_SLUG => $this->getSlugFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_DATEFORMAT => $this->getFormatFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_GMT => $this->getGMTFilterInputProcessor(),
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
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

    public function getName(array $component): string
    {
        // Add a nice name, so that the URL params when filtering make sense
        return match ((string) $component[1]) {
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

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match ((string)$component[1]) {
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

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match ($component[1]) {
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

    public function getFilterInputDescription(array $component): ?string
    {
        return match ((string)$component[1]) {
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
