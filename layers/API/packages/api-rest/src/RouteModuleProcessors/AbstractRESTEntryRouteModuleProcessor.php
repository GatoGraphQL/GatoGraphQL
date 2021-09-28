<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\RESTAPI\Helpers\HookHelpers;
use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected ?string $restFieldsQuery = null;
    protected ?array $restFields = null;
    protected RESTDataStructureFormatter $restDataStructureFormatter;
    protected FieldQueryConvertorInterface $fieldQueryConvertor;

    #[Required]
    public function autowireAbstractRESTEntryRouteModuleProcessor(
        RESTDataStructureFormatter $restDataStructureFormatter,
        FieldQueryConvertorInterface $fieldQueryConvertor
    ) {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
        $this->fieldQueryConvertor = $fieldQueryConvertor;
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
