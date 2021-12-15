<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Validator\RequestValidator;

use PoPBackbone\GraphQLParser\Execution\Request;

interface RequestValidatorInterface
{
    public function validate(Request $request);
}
