<?php

declare(strict_types=1);

namespace PoP\AccessControl\ConditionalOnModule\CacheControl\RelationalTypeResolverDecorators;

use PoP\AccessControl\Registries\AccessControlValidationDirectiveResolverRegistryInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\CacheControl\ASTNodes\CacheControlASTNodesFactory;
use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

class NoCacheAccessControlValidationRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    private ?AccessControlValidationDirectiveResolverRegistryInterface $accessControlValidationDirectiveResolverRegistry = null;

    final protected function getAccessControlValidationDirectiveResolverRegistry(): AccessControlValidationDirectiveResolverRegistryInterface
    {
        if ($this->accessControlValidationDirectiveResolverRegistry === null) {
            /** @var AccessControlValidationDirectiveResolverRegistryInterface */
            $accessControlValidationDirectiveResolverRegistry = $this->instanceManager->getInstance(AccessControlValidationDirectiveResolverRegistryInterface::class);
            $this->accessControlValidationDirectiveResolverRegistry = $accessControlValidationDirectiveResolverRegistry;
        }
        return $this->accessControlValidationDirectiveResolverRegistry;
    }

    /**
     * @return array<class-string<RelationalTypeResolverInterface>|string> Either the class, or the constant "*" to represent _any_ class
     */
    public function getRelationalTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractRelationalTypeResolver::class,
        ];
    }

    /**
     * Do not cache the response for any ACL validation directive
     *
     * @return array<string,Directive[]>
     */
    public function getPrecedingMandatoryDirectivesForDirectives(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $noCacheControlDirective = CacheControlASTNodesFactory::getNoCacheDirective();
        $precedingMandatoryDirectivesForDirectives = [];
        foreach ($this->getAccessControlValidationDirectiveResolverRegistry()->getAccessControlValidationDirectiveResolvers() as $directiveResolver) {
            $precedingMandatoryDirectivesForDirectives[$directiveResolver->getDirectiveName()] = [
                $noCacheControlDirective,
            ];
        }
        return $precedingMandatoryDirectivesForDirectives;
    }
}
