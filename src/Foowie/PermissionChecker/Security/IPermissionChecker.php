<?php

namespace Foowie\PermissionChecker\Security;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IPermissionChecker {

	/**
	 * @return bool
	 */
	function isAllowed($element);
}