<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <danrob@seznam.cz>
 */
interface IPermissionChecker {

	/**
	 * @return bool
	 */
	function isAllowed($element);
}