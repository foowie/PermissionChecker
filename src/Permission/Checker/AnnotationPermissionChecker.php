<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <danrob@seznam.cz>
 */
class AnnotationPermissionChecker extends \Nette\Object implements IPermissionChecker {

	/** @var \Nette\Security\User */
	protected $user;

	function __construct(\Nette\Security\User $user) {
		$this->user = $user;
	}

	/**
	 * @param \Reflection $element
	 * @return bool
	 */
	public function isAllowed($element) {
		return $this->checkResources($element) && $this->checkRoles($element) && $this->checkLoggedIn($element);
	}

	protected function checkRoles($element) {
		if ($element->hasAnnotation('role')) {
			$roles = (array) $element->getAnnotation('role');
			foreach ($roles as $role)
				if ($this->user->isInRole($role))
					return true;
			return false;
		}
		return true;
	}

	protected function checkResources($element) {
		if ($element->hasAnnotation('resource')) {
			$resources = (array) $element->getAnnotation('resource');
			if (count($resources) != 1)
				throw new \Nette\InvalidStateException('Invalid annotation resource count!');
			foreach ($resources as $resource)
				if ($this->user->isAllowed($resource))
					return true;
			return false;
		}
		return true;
	}

	protected function checkLoggedIn($element) {
		if ($element->hasAnnotation('loggedIn')) {
			return $element->getAnnotation('loggedIn') == $this->user->isLoggedIn();
		}
		return true;
	}

}