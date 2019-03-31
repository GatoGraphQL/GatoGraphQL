<?php
namespace PoP\CMS;

class CMSLooseContract_Manager
{
    protected $requiredHooks = [];
    protected $implementedHooks = [];
    protected $requiredNames = [];
    protected $implementedNames = [];

    public function __construct()
    {
        CMSLooseContract_Manager_Factory::setInstance($this);
    }

    public function getNotImplementedRequiredHooks() {

        return array_diff(
            $this->requiredHooks,
            $this->implementedHooks
        );
    }

    public function requireHooks($hooks)
    {
        $this->requiredHooks = array_merge(
            $this->requiredHooks,
            $hooks
        );
    }

    public function implementHooks($hooks)
    {
        $this->implementedHooks = array_merge(
            $this->implementedHooks,
            $hooks
        );
    }

    public function getNotImplementedRequiredNames() {

        return array_diff(
            $this->requiredNames,
            $this->implementedNames
        );
    }

    public function requireNames($names)
    {
        $this->requiredNames = array_merge(
            $this->requiredNames,
            $names
        );
    }

    public function implementNames($names)
    {
        $this->implementedNames = array_merge(
            $this->implementedNames,
            $names
        );
    }
}

/**
 * Initialize
 */
new CMSLooseContract_Manager();
