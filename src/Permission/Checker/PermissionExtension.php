<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class PermissionExtension extends \Nette\Config\CompilerExtension {

	public function loadConfiguration() {

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('annotationPermissionChecker'))->setClass('Permission\Checker\AnnotationPermissionChecker');
		$builder->addDefinition($this->prefix('linkPermissionChecker'))->setClass('Permission\Checker\LinkPermissionChecker');
		
		$builder->getDefinition('nette.latte')->addSetup('Permission\Checker\PermissionMacros::install($service->getCompiler(?))', array(null));
	}

}
