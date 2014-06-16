<?php

namespace Foowie\PermissionChecker\Latte;

use Latte\Compiler;
use Latte\IMacro;
use Latte\MacroNode;
use Latte\PhpWriter;
use Nette\Object;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IfAllowedHrefMacro extends Object implements IMacro {

	/** @var Compiler */
	protected $compiler;

	public static function install(Compiler $compiler) {
		$ifAllowedHrefMacro = new static($compiler);
		$compiler->addMacro('ifAllowedHref', $ifAllowedHrefMacro);
		$compiler->addMacro('allowedHref', $ifAllowedHrefMacro);
	}

	/**
	 * @param Compiler $compiler
	 */
	function __construct($compiler) {
		$this->compiler = $compiler;
	}

	public function initialize() {
	}

	public function finalize() {
	}

	/**
	 * New node is found. Returns FALSE to reject.
	 * @return bool
	 */
	public function nodeOpened(MacroNode $node) {
		$node->isEmpty = FALSE;
		$this->compiler->setContext(Compiler::CONTEXT_DOUBLE_QUOTED_ATTR);
		$res1 = $this->compile($node, array($this, 'macroHref'));
		$this->compiler->setContext(NULL);
		if (!$node->attrCode) {
			$node->attrCode = "<?php $res1 ?>";
		}

		$res2 = $this->compile($node, array('Foowie\PermissionChecker\Latte\PermissionMacros', 'macroIfAllowedLink'));
		if (!$node->openingCode) {
			$node->openingCode = "<?php $res2 ?>";
		}

		return $res1 !== false && $res2 !== false;
	}

	public function nodeClosed(MacroNode $node) {
		$res = $this->compile($node, array('Foowie\PermissionChecker\Latte\PermissionMacros', 'macroIfAllowedLinkEnd'));
		if (!$node->closingCode) {
			$node->closingCode = "<?php $res ?>";
		}
	}

	public function macroHref(MacroNode $node, PhpWriter $writer) {
		return $writer->write(' ?> href="<?php echo %escape(' . ($node->name === 'plink' ? '$_presenter' : '$_control') . '->link(%node.word, %node.array?)) ?>"<?php ');
	}

	private function compile(MacroNode $node, $def) {
		$node->tokenizer->reset();
		$writer = PhpWriter::using($node, $this->compiler);
		if (is_string($def)) {
			return $writer->write($def);
		} else {
			return callback($def)->invoke($node, $writer);
		}
	}
}