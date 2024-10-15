<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Command;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ComposerReplaceEntriesRemover;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symplify\MonorepoBuilder\Validator\SourcesPresenceValidator;
use Symplify\PackageBuilder\Console\Command\AbstractSymplifyCommand;
use Symplify\PackageBuilder\Console\Command\CommandNaming;

final class RemoveComposerReplaceEntriesCommand extends AbstractSymplifyCommand
{
    /**
     * @var string
     */
    private const COMPOSER_PATH = 'composer-path';
    /**
     * @var string
     */
    private const EXCLUDE_REPLACE = 'exclude-replace';

    public function __construct(
        private ComposerReplaceEntriesRemover $composerReplaceEntriesRemover,
        private SourcesPresenceValidator $sourcesPresenceValidator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Remove the "replace" entries in composer.json');
        $this->addArgument(
            self::COMPOSER_PATH,
            InputArgument::REQUIRED,
            'Path to the "composer.json" file'
        );
        $this->addOption(
            self::EXCLUDE_REPLACE,
            null,
            InputOption::VALUE_OPTIONAL,
            'Packages that must not be removed from the "replace" section.',
            ''
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->sourcesPresenceValidator->validateRootComposerJsonName();

        /** @var string */
        $composerPath = $input->getArgument(self::COMPOSER_PATH);

        /** @var string */
        $excludeReplace = $input->getOption(self::EXCLUDE_REPLACE);
        $excludeReplacePackageNames = explode(' ', $excludeReplace);

        $this->composerReplaceEntriesRemover->removeReplaceEntries(
            $composerPath,
            $excludeReplacePackageNames
        );

        $successMessage = 'The "replace" entries in composer.json were removed.';
        $this->symfonyStyle->success($successMessage);

        return self::SUCCESS;
    }
}
