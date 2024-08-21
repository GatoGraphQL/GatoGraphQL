<?php

declare(strict_types=1);

namespace PoP\Root;

use Exception;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Helpers\AppThreadHelpers;
use PoP\Root\HttpFoundation\InputBag;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\StateManagers\AppStateManager;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\HookManager;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\Root\StateManagers\ModuleManager;
use PoP\Root\StateManagers\ModuleManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * Single class hosting all the top-level instances
 * to run the application. Only a single AppThread
 * will be active at a single time, and its state
 * will be accessed/modified by the whole application.
 * Access the current AppThread via the corresponding
 * methods in the `App` facade class.
 *
 * It keeps all state in the application stored and
 * accessible in a single place, so that regenerating
 * this class provides a new state.
 *
 * Needed for PHPUnit.
 */
class AppThread implements AppThreadInterface
{
    protected AppLoaderInterface $appLoader;
    protected HookManagerInterface $hookManager;
    protected Request $request;
    protected Response $response;
    protected ContainerBuilderFactory $containerBuilderFactory;
    protected SystemContainerBuilderFactory $systemContainerBuilderFactory;
    protected ModuleManagerInterface $moduleManager;
    protected AppStateManagerInterface $appStateManager;
    /** @var array<class-string<ModuleInterface>> */
    protected array $moduleClassesToInitialize = [];
    protected bool $isHTTPRequest;
    protected ?string $uniqueID = null;

    /**
     * @param array<string,mixed> $context
     */
    public function __construct(
        private ?string $name = null,
        private array $context = [],
    ) {
    }

    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     */
    public function initialize(
        ?AppLoaderInterface $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?Request $request = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ModuleManagerInterface $moduleManager = null,
        ?AppStateManagerInterface $appStateManager = null,
    ): void {
        $this->appLoader = $appLoader ?? $this->createAppLoader();
        $this->hookManager = $hookManager ?? $this->createHookManager();
        $this->request = $request ?? $this->createRequest();
        $this->containerBuilderFactory = $containerBuilderFactory ?? $this->createContainerBuilderFactory();
        $this->systemContainerBuilderFactory = $systemContainerBuilderFactory ?? $this->createSystemContainerBuilderFactory();
        $this->moduleManager = $moduleManager ?? $this->createComponentManager();
        $this->appStateManager = $appStateManager ?? $this->createAppStateManager();

        $this->setResponse($this->createResponse());

        // Inject the Components slated for initialization
        $this->appLoader->addModuleClassesToInitialize($this->moduleClassesToInitialize);
        $this->moduleClassesToInitialize = [];

        /**
         * Indicate if this App is invoked via an HTTP request.
         * If not, it may be directly invoked as a PHP component,
         * or from a PHPUnit test.
         */
        $this->isHTTPRequest = $this->server('REQUEST_METHOD') !== null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Store properties for identifying across different
     * INTERNAL GraphQL servers, by storing the
     * persisted query for each in the context.
     *
     * @return array<string,mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Combination of the Name and Context
     * to uniquely identify the AppThread
     */
    public function getUniqueID(): string
    {
        if ($this->uniqueID === null) {
            $this->uniqueID = AppThreadHelpers::getUniqueID(
                $this->getName(),
                $this->getContext()
            );
        }
        return $this->uniqueID;
    }

    protected function createAppLoader(): AppLoaderInterface
    {
        return new AppLoader();
    }

    protected function createHookManager(): HookManagerInterface
    {
        return new HookManager();
    }

    /**
     * If an exception is thrown, create the Request
     * without the $_FILES
     *
     * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2794
     */
    protected function createRequest(): Request
    {
        try {
            return Request::createFromGlobals();
        } catch (Exception) {
        }

        return $this->createRequestWithoutFiles();
    }

    /**
     * Copied logic from Symfony
     *
     * @see vendor/symfony/http-foundation/Request.php
     */
    protected function createRequestWithoutFiles(): Request
    {
        $request = new Request(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            [], // $_FILES is producing the exception, so comment out
            $_SERVER,
        );

        if (str_starts_with($request->headers->get('CONTENT_TYPE', ''), 'application/x-www-form-urlencoded')
            && \in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new InputBag($data);
        }

        return $request;
    }

    /**
     * @see https://symfony.com/doc/current/components/http_foundation.html#response
     */
    protected function createResponse(): Response
    {
        return new Response();
    }

    protected function createContainerBuilderFactory(): ContainerBuilderFactory
    {
        return new ContainerBuilderFactory();
    }

    protected function createSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return new SystemContainerBuilderFactory();
    }

    protected function createComponentManager(): ModuleManagerInterface
    {
        return new ModuleManager();
    }

    protected function createAppStateManager(): AppStateManagerInterface
    {
        return new AppStateManager();
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getAppLoader(): AppLoaderInterface
    {
        return $this->appLoader;
    }

    public function getHookManager(): HookManagerInterface
    {
        return $this->hookManager;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return $this->containerBuilderFactory;
    }

    public function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return $this->systemContainerBuilderFactory;
    }

    public function getModuleManager(): ModuleManagerInterface
    {
        return $this->moduleManager;
    }

    public function getAppStateManager(): AppStateManagerInterface
    {
        return $this->appStateManager;
    }

    public function isHTTPRequest(): bool
    {
        return $this->isHTTPRequest;
    }

    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public function stockAndInitializeModuleClasses(
        array $moduleClasses
    ): void {
        $this->moduleClassesToInitialize = array_merge(
            $this->moduleClassesToInitialize,
            $moduleClasses
        );
    }

    /**
     * Shortcut function.
     */
    final public function getContainer(): ContainerInterface
    {
        return $this->containerBuilderFactory->getInstance();
    }

    /**
     * Shortcut function.
     */
    final public function getSystemContainer(): ContainerInterface
    {
        return $this->systemContainerBuilderFactory->getInstance();
    }

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException
     */
    final public function getModule(string $moduleClass): ModuleInterface
    {
        return $this->moduleManager->getModule($moduleClass);
    }

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public function getState(string|array $keyOrPath): mixed
    {
        $appStateManager = $this->appStateManager;
        if (is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->getUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->get($key);
    }

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public function hasState(string|array $keyOrPath): mixed
    {
        $appStateManager = $this->appStateManager;
        if (is_array($keyOrPath)) {
            /** @var string[] */
            $path = $keyOrPath;
            return $appStateManager->hasUnder($path);
        }
        /** @var string */
        $key = $keyOrPath;
        return $appStateManager->has($key);
    }

    /**
     * Shortcut function.
     */
    final public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->hookManager->addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    final public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->hookManager->removeFilter($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    final public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return $this->hookManager->applyFilters($tag, $value, ...$args);
    }
    /**
     * Shortcut function.
     */
    final public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        $this->hookManager->addAction($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    final public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return $this->hookManager->removeAction($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    final public function doAction(string $tag, mixed ...$args): void
    {
        $this->hookManager->doAction($tag, ...$args);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     */
    final public function request(string $key, mixed $default = null): mixed
    {
        /**
         * `get` doesn't support arrays, then use ->all for that case
         *
         * @see https://symfony.com/doc/current/components/http_foundation.html#accessing-request-data
         */
        try {
            return $this->request->request->get($key, $default);
        } catch (BadRequestException) {
            return $this->request->request->all($key);
        }
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     */
    final public function query(string $key, mixed $default = null): mixed
    {
        /**
         * `get` doesn't support arrays, then use ->all for that case
         *
         * @see https://symfony.com/doc/current/components/http_foundation.html#accessing-request-data
         */
        try {
            return $this->request->query->get($key, $default);
        } catch (BadRequestException) {
            return $this->request->query->all($key);
        }
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     */
    final public function cookies(string $key, mixed $default = null): mixed
    {
        return $this->request->cookies->get($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     */
    final public function files(string $key, mixed $default = null): mixed
    {
        return $this->request->files->get($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     */
    final public function server(string $key, mixed $default = null): mixed
    {
        return $this->request->server->get($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     */
    final public function headers(string $key, mixed $default = null): mixed
    {
        return $this->request->headers->get($key, $default);
    }
}
