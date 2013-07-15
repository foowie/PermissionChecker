<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <danrob@seznam.cz>
 */
class LinkPermissionChecker extends \Nette\Object {

	/** @var \Nette\Application\IPresenterFactory */
	protected $presenterFactory;

	/** @var \Nette\Application\Application */
	protected $application;

	/** @var IPermissionChecker */
	protected $permissionChecker;

	function __construct(\Nette\Application\IPresenterFactory $presenterFactory, \Nette\Application\Application $application, IPermissionChecker $permissionChecker) {
		$this->presenterFactory = $presenterFactory;
		$this->application = $application;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * Check whenever current user is allowed to use given link
	 * @param string $link etc "this", ":Admin:Show:default"
	 * @return bool
	 */
	public function isAllowed($link) {
		list($presenter, $action) = $this->formatLink($link);
		$presenterReflection = \Nette\Application\UI\PresenterComponentReflection::from($this->presenterFactory->getPresenterClass($presenter));
		if (!$this->permissionChecker->isAllowed($presenterReflection))
			return false;
		$actionKey = \Nette\Application\UI\Presenter::ACTION_KEY . ucfirst($action);
		if ($presenterReflection->hasMethod($actionKey) && !$this->permissionChecker->isAllowed($presenterReflection->getMethod($actionKey)))
			return false;
		return true;
	}

	/**
	 * Format link to format array('module:submodule:presenter', 'action')
	 * @return array(presenter, action)
	 */
	public function formatLink($destination) {
		if($destination == 'this')
			return array($this->application->presenter->getName(), $this->application->presenter->getAction());
		
		$parts = explode(':', $destination);
		if ($destination[0] != ':') {
			$current = explode(':', $this->application->presenter->getName());
			if (strpos($destination, ':') !== false)
				array_pop($current); // remove presenter
			$parts = array_merge($current, $parts);
		} else {
			array_shift($parts); // remove empty
		}

		if ($destination[strlen($destination) - 1] == ':') {
			array_pop($parts); // remove empty
			$action = \Nette\Application\UI\Presenter::DEFAULT_ACTION;
		} else {
			$action = array_pop($parts);
		}
		return array(implode(':', $parts), $action);
	}

}