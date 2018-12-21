<?php
namespace PoP\Engine;

abstract class CheckpointHelperBase {

	public static function get_checkpoint_configuration($name) {

		// If there is no checkpoint, then $name failed to find it, which is a bug, so raise an exception
		throw new \Exception(sprintf('No checkpoint configuration found with name \'%s\' (%s)', $name, full_url()));
	}
}