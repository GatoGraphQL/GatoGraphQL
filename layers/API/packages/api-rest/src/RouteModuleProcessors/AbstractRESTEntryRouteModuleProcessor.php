<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoP\RESTAPI\Helpers\HookHelpers;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected ?string $restFieldsQuery = null;
    protected ?array $restFields = null;

    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;
    private ?FieldQueryConvertorInterface $fieldQueryConvertor = null;

    public function setRESTDataStructureFormatter(RESTDataStructureFormatter $restDataStructureFormatter): void
    {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
    }
    protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        return $this->restDataStructureFormatter ??= $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
    }
    public function setFieldQueryConvertor(FieldQueryConvertorInterface $fieldQueryConvertor): void
    {
        $this->fieldQueryConvertor = $fieldQueryConvertor;
    }
    protected function getFieldQueryConvertor(): FieldQueryConvertorInterface
    {
        return $this->fieldQueryConvertor ??= $this->instanceManager->getInstance(FieldQueryConvertorInterface::class);
    }

    public function getRESTFields(): array
    {
        if (is_null($this->restFields)) {
            $restFields = $this->getRESTFieldsQuery();
            $fieldQuerySet = $this->getFieldQueryConvertor()->convertAPIQuery($restFields);
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
