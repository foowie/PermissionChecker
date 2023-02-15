<?php

namespace Foowie\PermissionChecker\Security;

use Nette\Application\UI\ComponentReflection;
use Nette\InvalidStateException;
use Nette\Security\User;
use Nette\SmartObject;
use ReflectionClass;
use ReflectionMethod;
use Reflector;

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
	 * @param ReflectionClass|ReflectionMethod  $element
	 */
	public function isAllowed(Reflector $element): bool {
		return $this->checkResources($element) && $this->checkRoles($element) && $this->checkLoggedIn($element);
	}

	/**
	 * @param ReflectionClass|ReflectionMethod  $element
	 */
	protected function checkRoles(Reflector $element): bool {
		$roles = (array) $this->getAnnotation($element, 'role');
		if ($roles) {
			foreach ($roles as $role) {
				if ($this->user->isInRole($role)) {
					return true;
				}
			}
			return false;
		}
		return true;
	}

	/**
	 * @param ReflectionClass|ReflectionMethod  $element
	 */
	protected function checkResources(Reflector $element): bool {
		$resources = (array) $this->getAnnotation($element, 'resource');
		if ($resources) {
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

	/**
	 * @param ReflectionClass|ReflectionMethod  $element
	 */
	protected function checkLoggedIn(Reflector $element): bool {
		$loggedIn = $this->getAnnotation($element, 'loggedIn');
		if ($loggedIn) {
			return $loggedIn == $this->user->isLoggedIn();
		}
		return true;
	}

	/**
	 * @param ReflectionClass|ReflectionMethod  $element
	 * @return mixed[]
	 */
	protected function getAnnotation(Reflector $element, string $name): ?array {
		return ComponentReflection::parseAnnotation($element, $name);
	}
}