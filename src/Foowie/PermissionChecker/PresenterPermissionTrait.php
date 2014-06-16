<?php


namespace Foowie\PermissionChecker;


use Nette\Application\ForbiddenRequestException;

trait PresenterPermissionTrait {

	public function checkRequirements($element) {
		parent::checkRequirements($element);
		if (!$this->context->getByType('Foowie\PermissionChecker\Security\IPermissionChecker')->isAllowed($element)) {
			throw new ForbiddenRequestException();
		}
	}

} 