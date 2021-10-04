<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\RESTAPI\Helpers\HookHelpers;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected ?string $restFieldsQuery = null;
    protected ?array $restFields = null;
    protected RESTDataStructureFormatter $restDataStructureFormatter;
    protected FieldQueryConvertorInterface $fieldQueryConvertor;

    #[Required]
    final public function autowireAbstractRESTEntryRouteModuleProcessor(
        RESTDataStructureFormatter $restDataStructureFormatter,
        FieldQueryConvertorInterface $fieldQueryConvertor
    ): void {
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
