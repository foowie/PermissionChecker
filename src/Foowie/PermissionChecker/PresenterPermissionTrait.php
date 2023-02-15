<?php


namespace Foowie\PermissionChecker;

use Nette\Application\ForbiddenRequestException;

trait PresenterPermissionTrait {

	/** @var \Foowie\PermissionChecker\Security\IPermissionChecker @inject */
	public $permissionChecker;

	public function checkRequirements($element): void {
		parent::checkRequirements($element);
		if (!$this->permissionChecker->isAllowed($element)) {
			throw new ForbiddenRequestException();
		}
	}

}