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

    final public function setAccessControlValidationDirectiveResolverRegistry(AccessControlValidationDirectiveResolverRegistryInterface $accessControlValidationDirectiveResolverRegistry): void
    {
        $this->accessControlValidationDirectiveResolverRegistry = $accessControlValidationDirectiveResolverRegistry;
    }
    final protected function getAccessControlValidationDirectiveResolverRegistry(): AccessControlValidationDirectiveResolverRegistryInterface
    {
        /** @var AccessControlValidationDirectiveResolverRegistryInterface */
        return $this->accessControlValidationDirectiveResolverRegistry ??= $this->instanceManager->getInstance(AccessControlValidationDirectiveResolverRegistryInterface::class);
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
