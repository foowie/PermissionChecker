<?php

namespace Foowie\PermissionChecker\Latte;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class PermissionMacros extends MacroSet {
	
	public static function install(Compiler $compiler): void {
		$me = new static($compiler);
		IfAllowedHrefMacro::install($compiler);
		$me->addMacro('ifAllowedLink', array(get_called_class(), 'macroIfAllowedLink'), array(get_called_class(), 'macroIfAllowedLinkEnd'));
		$me->addMacro('ifAllowed', array(get_called_class(), 'macroIfAllowed'), array(get_called_class(), 'macroIfAllowedEnd'));
	}
	
	public static function macroIfAllowedLink(MacroNode $node, PhpWriter $writer): string {
		return $writer->write('if($presenter->context->getByType("Foowie\PermissionChecker\Security\LinkPermissionChecker")->isAllowed(%node.word)){');
	}	
	
	public static function macroIfAllowedLinkEnd(MacroNode $node, PhpWriter $writer): string {
		return $writer->write('}');
	}
	
	public static function macroIfAllowed(MacroNode $node, PhpWriter $writer): string {
		return $writer->write('if($user->isAllowed(%node.word)){');
	}	
	
	public static function macroIfAllowedEnd(MacroNode $node, PhpWriter $writer): string {
		return $writer->write('}');
	}
	
}