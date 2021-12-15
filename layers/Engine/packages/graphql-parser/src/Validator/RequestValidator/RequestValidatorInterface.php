<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Validator\RequestValidator;

use PoP\GraphQLParser\Execution\Request;

interface RequestValidatorInterface
{
    public function validate(Request $request);
}
