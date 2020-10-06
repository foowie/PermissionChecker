<?php

namespace Foowie\PermissionChecker\Security;

use Nette\Application\UI\ComponentReflection;
use Nette\InvalidStateException;
use Nette\Security\User;
use Nette\SmartObject;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class AnnotationPermissionChecker implements IPermissionChecker {
	use SmartObject;

	/** @var User */
	protected $user;

	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
	 * @param \Reflector $element
	 * @return bool
	 */
	public function isAllowed($element) {
		return $this->checkResources($element) && $this->checkRoles($element) && $this->checkLoggedIn($element);
	}

	protected function checkRoles($element) {
		if ($element->hasAnnotation('role')) {
			$roles = (array) $this->getAnnotation($element, 'role');
			foreach ($roles as $role) {
				if ($this->user->isInRole($role)) {
					return true;
				}
			}
			return false;
		}
		return true;
	}

	protected function checkResources($element) {
		if ($element->hasAnnotation('resource')) {
			$resources = (array) $this->getAnnotation($element, 'resource');
			if (count($resources) != 1) {
				throw new InvalidStateException('Invalid annotation resource count!');
			}
			foreach ($resources as $resource) {
				if ($this->user->isAllowed($resource)) {
					return true;
				}
			}
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

	protected function getAnnotation(\Reflector $element, $name) {
		return ComponentReflection::parseAnnotation($element, $name);
	}
}