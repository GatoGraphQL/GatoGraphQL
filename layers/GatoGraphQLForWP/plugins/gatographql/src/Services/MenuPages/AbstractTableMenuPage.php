<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Admin\Tables\TableInterface;
use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Table menu page
 */
abstract class AbstractTableMenuPage extends AbstractPluginMenuPage
{
    protected ?TableInterface $tableObject;

    protected function getHeader(): string
    {
        return sprintf(
            \__('%s — %s', 'gatographql'),
            PluginApp::getMainPlugin()->getPluginName(),
            $this->getMenuPageTitle()
        );
    }

    protected function hasViews(): bool
    {
        return false;
    }

    public function print(): void
    {
        if ($this->tableObject === null) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo \esc_html($this->getHeader()) ?></h1>
            <?php $this->printHeader() ?>
            <?php $this->printBody() ?>
        </div>
        <?php
    }

    protected function printHeader(): void
    {
        if ($this->hasViews()) {
            /** @var TableInterface */
            $tableObject = $this->tableObject;
            $tableObject->views();
        }
    }

    protected function printBody(): void
    {
        ?>
            <form method="post">
                <?php
                    /** @var TableInterface */
                    $tableObject = $this->tableObject;
                    $tableObject->prepare_items();
                    $tableObject->display();
                ?>
            </form>
        <?php
    }

    protected function showScreenOptions(): bool
    {
        return false;
    }

    protected function getScreenOptionLabel(): string
    {
        return $this->getHeader();
    }
    protected function getScreenOptionDefault(): int
    {
        return 999;
    }
    protected function getScreenOptionName(): string
    {
        return str_replace(' ', '_', strtolower($this->getScreenOptionLabel())) . '_per_page';
    }

    /**
     * @return class-string<TableInterface>
     */
    abstract protected function getTableClass(): string;

    public function initializeTable(): void
    {
        /**
         * Screen options
         */
        if ($this->showScreenOptions()) {
            /**
             * Set-up the screen options
             */
            $option = 'per_page';
            $args = [
                'label' => $this->getScreenOptionLabel(),
                'default' => $this->getScreenOptionDefault(),
                'option'  => $this->getScreenOptionName(),
            ];
            \add_screen_option($option, $args);
        }

        $this->tableObject = $this->createTableObject();
    }

    protected function createTableObject(): TableInterface
    {
        /**
         * Instantiate the table object.
         *
         * It inherits from \WP_List_Table, which is not available
         * when defining services. Hence, cannot use the container.
         */
        $tableClass = $this->getTableClass();
        $tableObject = new $tableClass();

        return $tableObject;
    }

    public function initialize(): void
    {
        parent::initialize();

        if ($this->showScreenOptions()) {
            /**
             * Save the screen options
             */
            \add_filter(
                'set-screen-option',
                fn ($status, $option, $value) => $value,
                10,
                3
            );
        } else {
            /**
             * Remove the Screen Options tab
             */
            \add_filter('screen_options_show_screen', '__return_false');
        }

        /**
         * Priority: execute after all `addMenuPages`, so by then we have the hookName
         */
        \add_action(
            'admin_menu',
            function (): void {
                /**
                 * Attach to the hook corresponding to this page
                 */
                \add_action(
                    'load-' . $this->getHookName(),
                    $this->initializeTable(...)
                );
            },
            PHP_INT_MAX
        );
    }
}
