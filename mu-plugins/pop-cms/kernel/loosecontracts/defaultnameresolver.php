<?php
namespace PoP\CMS;

class DefaultNameResolver extends \PoP\CMS\NameResolver_Base
{
    protected $names = [];

	public function getName(string $name) {
		// If there's no entry, then use the original $hookName
        return $this->names[$name] ?? $name;
	}

    public function implementName(string $abstractName, string $implementationName) {
        parent::implementName($abstractName, $implementationName);
        $this->names[$abstractName] = $implementationName;
    }

    public function implementNames(array $names) {
        parent::implementNames($names);
        $this->names = array_merge(
            $this->names,
            $names
        );
    }
}

/**
 * Initialize
 */
new DefaultNameResolver();
