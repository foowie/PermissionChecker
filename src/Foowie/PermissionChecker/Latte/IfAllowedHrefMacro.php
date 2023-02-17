<?php

namespace Foowie\PermissionChecker\Latte;

use Latte\Compiler;
use Latte\Macro;
use Latte\MacroNode;
use Latte\PhpWriter;
use Nette\SmartObject;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class IfAllowedHrefMacro implements Macro {
	use SmartObject;

	/** @var Compiler */
	protected $compiler;

	public static function install(Compiler $compiler): void {
		$ifAllowedHrefMacro = new static($compiler);
		$compiler->addMacro('ifAllowedHref', $ifAllowedHrefMacro);
		$compiler->addMacro('allowedHref', $ifAllowedHrefMacro);
	}

	final public function __construct(Compiler $compiler) {
		$this->compiler = $compiler;
	}

	public function initialize(): void {
	}

	public function finalize(): ?array {
		return null;
	}

	/**
	 * New node is found. Returns FALSE to reject.
	 */
	public function nodeOpened(MacroNode $node): bool {
		$node->empty = false;
		$res1 = $this->compile($node, array($this, 'macroHref'));
		if (!$node->attrCode) {
			$node->attrCode = "<?php $res1 ?>";
		}

		$res2 = $this->compile($node, array('Foowie\PermissionChecker\Latte\PermissionMacros', 'macroIfAllowedLink'));
		if (!$node->openingCode) {
			$node->openingCode = "<?php $res2 ?>";
		}

		return $res1 !== '' && $res2 !== '';
	}

	public function nodeClosed(MacroNode $node): void {
		$res = $this->compile($node, array('Foowie\PermissionChecker\Latte\PermissionMacros', 'macroIfAllowedLinkEnd'));
		if (!$node->closingCode) {
			$node->closingCode = "<?php $res ?>";
		}
	}

	public function macroHref(MacroNode $node, PhpWriter $writer): string {
		return $writer->write(' ?> href="<?php echo %escape(' . ($node->name === 'plink' ? '$this->global->uiPresenter' : '$this->global->uiControl') . '->link(%node.word, %node.array?)) ?>"<?php ');
	}

	private function compile(MacroNode $node, callable $def): string {
		$node->tokenizer->reset();
		$writer = PhpWriter::using($node, $this->compiler);
		if (is_string($def)) {
			return $writer->write($def);
		} else {
			return call_user_func($def, $node, $writer);
		}
	}
}