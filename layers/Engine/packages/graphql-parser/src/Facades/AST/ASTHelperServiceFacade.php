<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Facades\AST;

use PoP\Root\App;
use PoP\GraphQLParser\AST\ASTHelperServiceInterface;

class ASTHelperServiceFacade
{
    public static function getInstance(): ASTHelperServiceInterface
    {
        /**
         * @var ASTHelperServiceInterface
         */
        $service = App::getContainer()->get(ASTHelperServiceInterface::class);
        return $service;
    }
}
