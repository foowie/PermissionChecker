<?php

namespace Foowie\PermissionChecker\DI;

use Nette\DI\CompilerExtension;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class PermissionExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('annotationPermissionChecker'))->setClass('Foowie\PermissionChecker\Security\AnnotationPermissionChecker');
		$builder->addDefinition($this->prefix('linkPermissionChecker'))->setClass('Foowie\PermissionChecker\Security\LinkPermissionChecker');

		$builder->getDefinition('nette.latteFactory')->addSetup('Foowie\PermissionChecker\Latte\PermissionMacros::install($service->getCompiler(?))', array(null));
	}

}
