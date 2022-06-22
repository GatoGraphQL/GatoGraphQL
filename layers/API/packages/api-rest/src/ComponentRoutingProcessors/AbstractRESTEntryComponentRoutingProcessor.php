<?php

declare(strict_types=1);

namespace PoPAPI\RESTAPI\ComponentRoutingProcessors;

use PoP\Root\App;
use PoPAPI\API\Schema\FieldQueryConvertorInterface;
use PoP\ComponentRouting\AbstractEntryComponentRoutingProcessor;
use PoPAPI\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;
use PoPAPI\RESTAPI\Helpers\HookHelpers;

abstract class AbstractRESTEntryComponentRoutingProcessor extends AbstractEntryComponentRoutingProcessor
{
    protected ?array $restEndpointGraphQLQuery = null;

    private ?RESTDataStructureFormatter $restDataStructureFormatter = null;
    private ?FieldQueryConvertorInterface $fieldQueryConvertor = null;

    final public function setRESTDataStructureFormatter(RESTDataStructureFormatter $restDataStructureFormatter): void
    {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
    }
    final protected function getRESTDataStructureFormatter(): RESTDataStructureFormatter
    {
        return $this->restDataStructureFormatter ??= $this->instanceManager->getInstance(RESTDataStructureFormatter::class);
    }
    final public function setFieldQueryConvertor(FieldQueryConvertorInterface $fieldQueryConvertor): void
    {
        $this->fieldQueryConvertor = $fieldQueryConvertor;
    }
    final protected function getFieldQueryConvertor(): FieldQueryConvertorInterface
    {
        return $this->fieldQueryConvertor ??= $this->instanceManager->getInstance(FieldQueryConvertorInterface::class);
    }

    public function getGraphQLQueryToResolveRESTEndpoint(): string
    {
        if ($this->restEndpointGraphQLQuery === null) {
            $this->restEndpointGraphQLQuery = (string) App::applyFilters(
                HookHelpers::getHookName(get_called_class()),
                $this->getInitialRESTFields()
            );
        }
        return $this->restEndpointGraphQLQuery;
    }

    abstract protected function getInitialRESTFields(): string;
}
