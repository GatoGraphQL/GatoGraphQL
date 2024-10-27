<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
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

    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getSortFilterInput(): SortFilterInput
    {
        if ($this->sortFilterInput === null) {
            /** @var SortFilterInput */
            $sortFilterInput = $this->instanceManager->getInstance(SortFilterInput::class);
            $this->sortFilterInput = $sortFilterInput;
        }
        return $this->sortFilterInput;
    }
    final protected function getExcludeIDsFilterInput(): ExcludeIDsFilterInput
    {
        if ($this->excludeIDsFilterInput === null) {
            /** @var ExcludeIDsFilterInput */
            $excludeIDsFilterInput = $this->instanceManager->getInstance(ExcludeIDsFilterInput::class);
            $this->excludeIDsFilterInput = $excludeIDsFilterInput;
        }
        return $this->excludeIDsFilterInput;
    }
    final protected function getExcludeParentIDsFilterInput(): ExcludeParentIDsFilterInput
    {
        if ($this->excludeParentIDsFilterInput === null) {
            /** @var ExcludeParentIDsFilterInput */
            $excludeParentIDsFilterInput = $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
            $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
        }
        return $this->excludeParentIDsFilterInput;
    }
    final protected function getFormatFilterInput(): FormatFilterInput
    {
        if ($this->formatFilterInput === null) {
            /** @var FormatFilterInput */
            $formatFilterInput = $this->instanceManager->getInstance(FormatFilterInput::class);
            $this->formatFilterInput = $formatFilterInput;
        }
        return $this->formatFilterInput;
    }
    final protected function getGMTFilterInput(): GMTFilterInput
    {
        if ($this->gmtFilterInput === null) {
            /** @var GMTFilterInput */
            $gmtFilterInput = $this->instanceManager->getInstance(GMTFilterInput::class);
            $this->gmtFilterInput = $gmtFilterInput;
        }
        return $this->gmtFilterInput;
    }
    final protected function getIncludeFilterInput(): IncludeFilterInput
    {
        if ($this->includeFilterInput === null) {
            /** @var IncludeFilterInput */
            $includeFilterInput = $this->instanceManager->getInstance(IncludeFilterInput::class);
            $this->includeFilterInput = $includeFilterInput;
        }
        return $this->includeFilterInput;
    }
    final protected function getLimitFilterInput(): LimitFilterInput
    {
        if ($this->limitFilterInput === null) {
            /** @var LimitFilterInput */
            $limitFilterInput = $this->instanceManager->getInstance(LimitFilterInput::class);
            $this->limitFilterInput = $limitFilterInput;
        }
        return $this->limitFilterInput;
    }
    final protected function getOffsetFilterInput(): OffsetFilterInput
    {
        if ($this->offsetFilterInput === null) {
            /** @var OffsetFilterInput */
            $offsetFilterInput = $this->instanceManager->getInstance(OffsetFilterInput::class);
            $this->offsetFilterInput = $offsetFilterInput;
        }
        return $this->offsetFilterInput;
    }
    final protected function getParentIDFilterInput(): ParentIDFilterInput
    {
        if ($this->parentIDFilterInput === null) {
            /** @var ParentIDFilterInput */
            $parentIDFilterInput = $this->instanceManager->getInstance(ParentIDFilterInput::class);
            $this->parentIDFilterInput = $parentIDFilterInput;
        }
        return $this->parentIDFilterInput;
    }
    final protected function getParentIDsFilterInput(): ParentIDsFilterInput
    {
        if ($this->parentIDsFilterInput === null) {
            /** @var ParentIDsFilterInput */
            $parentIDsFilterInput = $this->instanceManager->getInstance(ParentIDsFilterInput::class);
            $this->parentIDsFilterInput = $parentIDsFilterInput;
        }
        return $this->parentIDsFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        if ($this->searchFilterInput === null) {
            /** @var SearchFilterInput */
            $searchFilterInput = $this->instanceManager->getInstance(SearchFilterInput::class);
            $this->searchFilterInput = $searchFilterInput;
        }
        return $this->searchFilterInput;
    }
    final protected function getSlugFilterInput(): SlugFilterInput
    {
        if ($this->slugFilterInput === null) {
            /** @var SlugFilterInput */
            $slugFilterInput = $this->instanceManager->getInstance(SlugFilterInput::class);
            $this->slugFilterInput = $slugFilterInput;
        }
        return $this->slugFilterInput;
    }
    final protected function getSlugsFilterInput(): SlugsFilterInput
    {
        if ($this->slugsFilterInput === null) {
            /** @var SlugsFilterInput */
            $slugsFilterInput = $this->instanceManager->getInstance(SlugsFilterInput::class);
            $this->slugsFilterInput = $slugsFilterInput;
        }
        return $this->slugsFilterInput;
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_SORT,
            self::COMPONENT_FILTERINPUT_LIMIT,
            self::COMPONENT_FILTERINPUT_OFFSET,
            self::COMPONENT_FILTERINPUT_SEARCH,
            self::COMPONENT_FILTERINPUT_IDS,
            self::COMPONENT_FILTERINPUT_ID,
            self::COMPONENT_FILTERINPUT_COMMASEPARATED_IDS,
            self::COMPONENT_FILTERINPUT_EXCLUDE_IDS,
            self::COMPONENT_FILTERINPUT_PARENT_IDS,
            self::COMPONENT_FILTERINPUT_PARENT_ID,
            self::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS,
            self::COMPONENT_FILTERINPUT_SLUGS,
            self::COMPONENT_FILTERINPUT_SLUG,
            self::COMPONENT_FILTERINPUT_DATEFORMAT,
            self::COMPONENT_FILTERINPUT_GMT,
        );
    }

    public function getFilterInput(Component $component): ?FilterInputInterface
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

    public function getInputClass(Component $component): string
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

    public function getName(Component $component): string
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

    public function getFilterInputTypeResolver(Component $component): InputTypeResolverInterface
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

    public function getFilterInputTypeModifiers(Component $component): int
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

    public function getFilterInputDescription(Component $component): ?string
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
