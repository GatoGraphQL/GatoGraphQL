<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI;

use Psr\Http\Message\ResponseInterface;
use stdClass;

class RESTResponse
{
    public function __construct(
        public string $status = '',
        public string $message = '',
        /**
         * Extra data
         */
        public stdClass $data = new stdClass(),
    ) {
    }

    public static function fromClientResponse(ResponseInterface $clientResponse): self
    {
        $clientResponseContents = json_decode($clientResponse->getBody()->__toString());
        $restResponse = new self();
        $restResponse->status = $clientResponseContents->status;
        $restResponse->message = $clientResponseContents->message;
        $restResponse->data = (object) $clientResponseContents->data;
        return $restResponse;
    }
}
