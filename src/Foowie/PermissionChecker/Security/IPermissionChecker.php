<?php

namespace Foowie\PermissionChecker\Security;

use Reflector;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IPermissionChecker {

	function isAllowed(Reflector $element): bool;
}