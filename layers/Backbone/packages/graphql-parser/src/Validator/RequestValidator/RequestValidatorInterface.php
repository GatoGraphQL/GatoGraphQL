<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Validator\RequestValidator;

use PoPBackbone\GraphQLParser\Execution\RequestInterface;

interface RequestValidatorInterface
{
    public function validate(RequestInterface $request);
}
