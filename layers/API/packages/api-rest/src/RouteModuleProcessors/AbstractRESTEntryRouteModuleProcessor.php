<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\RESTAPI\Helpers\HookHelpers;
use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected ?string $restFieldsQuery = null;
    protected ?array $restFields = null;
    
    function __construct(
        HooksAPIInterface $hooksAPI,
        protected RESTDataStructureFormatter $restDataStructureFormatter,
        protected FieldQueryConvertorInterface $fieldQueryConvertor
    ) {
        parent::__construct($hooksAPI);
    }

    public function getRESTFields(): array
    {
        if (is_null($this->restFields)) {
            $restFields = $this->getRESTFieldsQuery();
            $fieldQuerySet = $this->fieldQueryConvertor->convertAPIQuery($restFields);
            $this->restFields = $fieldQuerySet->getRequestedFieldQuery();
        }
        return $this->restFields;
    }

    public function getRESTFieldsQuery(): string
    {
        if (is_null($this->restFieldsQuery)) {
            $this->restFieldsQuery = (string) $this->hooksAPI->applyFilters(
                HookHelpers::getHookName(get_called_class()),
                $this->getInitialRESTFields()
            );
        }
        return $this->restFieldsQuery;
    }
    
    abstract protected function getInitialRESTFields(): string;
}
