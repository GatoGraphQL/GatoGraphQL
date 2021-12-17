<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Validator\RequestValidator;

use PoPBackbone\GraphQLParser\Execution\Interfaces\RequestInterface;

interface RequestValidatorInterface
{
    public function validate(RequestInterface $request);
}
