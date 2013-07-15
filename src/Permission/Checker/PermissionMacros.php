<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <danrob@seznam.cz>
 */
class PermissionMacros extends \Nette\Latte\Macros\MacroSet {
	
	public static function install(\Nette\Latte\Compiler $compiler) {
		$me = new static($compiler);
		IfAllowedHrefMacro::install($compiler);
		$me->addMacro('ifAllowedLink', array('Permission\Checker\PermissionMacros', 'macroIfAllowedLink'), array('Permission\Checker\PermissionMacros', 'macroIfAllowedLinkEnd'));
		$me->addMacro('ifAllowed', array('Permission\Checker\PermissionMacros', 'macroIfAllowed'), array('Permission\Checker\PermissionMacros', 'macroIfAllowedEnd'));
	}
	
	public static function macroIfAllowedLink(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('if($presenter->context->getByType("Permission\Checker\LinkPermissionChecker")->isAllowed(%node.word)):');
	}	
	
	public static function macroIfAllowedLinkEnd(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('endif;');
	}
	
	public static function macroIfAllowed(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('if($user->isAllowed(%node.word)):');
	}	
	
	public static function macroIfAllowedEnd(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write('endif;');
	}
	
}