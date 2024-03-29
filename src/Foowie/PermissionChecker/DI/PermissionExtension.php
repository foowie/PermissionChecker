<?php

namespace Foowie\PermissionChecker\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class PermissionExtension extends CompilerExtension {

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('annotationPermissionChecker'))->setType('Foowie\PermissionChecker\Security\AnnotationPermissionChecker');
		$builder->addDefinition($this->prefix('linkPermissionChecker'))->setType('Foowie\PermissionChecker\Security\LinkPermissionChecker');

		/** @var FactoryDefinition $latteFactory */
		$latteFactory = $builder->getDefinition('nette.latteFactory');
		$latteFactory->getResultDefinition()
			->addSetup('Foowie\PermissionChecker\Latte\PermissionMacros::install($service->getCompiler(?))', [null]);
	}

}
