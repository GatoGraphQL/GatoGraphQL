<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\API\Facades\FieldQueryConvertorFacade;
use PoP\RESTAPI\Helpers\HookHelpers;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected ?string $restFieldsQuery = null;
    protected array $restFields = [];
    
    function __construct(protected RESTDataStructureFormatter $restDataStructureFormatter)
    {
    }

    public function getRESTFields(): array
    {
        if (is_null($this->restFields)) {
            $restFields = $this->getRESTFieldsQuery();
            $fieldQueryConvertor = FieldQueryConvertorFacade::getInstance();
            $fieldQuerySet = $fieldQueryConvertor->convertAPIQuery($restFields);
            $this->restFields = $fieldQuerySet->getRequestedFieldQuery();
        }
        return $this->restFields;
    }

    public function getRESTFieldsQuery(): string
    {
        if (is_null($this->restFieldsQuery)) {
            $this->restFieldsQuery = (string) HooksAPIFacade::getInstance()->applyFilters(
                HookHelpers::getHookName(get_called_class()),
                $this->getInitialRESTFields()
            );
        }
        return $this->restFieldsQuery;
    }
    
    abstract protected function getInitialRESTFields(): string;
}
