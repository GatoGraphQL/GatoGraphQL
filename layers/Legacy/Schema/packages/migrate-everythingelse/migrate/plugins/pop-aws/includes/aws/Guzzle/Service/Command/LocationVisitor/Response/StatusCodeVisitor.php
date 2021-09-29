<?php

namespace Guzzle\Service\Command\LocationVisitor\Response;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\Parameter;

/**
 * Location visitor used to add the status code of a response to a key in the result
 */
class StatusCodeVisitor extends AbstractResponseVisitor
{
    public function visit(
        CommandInterface $command,
        Response $response,
        Parameter $param,
        &$value,
        $context =  null
    ) {
        $value[$param->getName()] = $response->getStatusCode();
    }
}
