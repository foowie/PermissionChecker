<?php

namespace Permission\Checker;

/**
 * @author Daniel Robenek <danrob@seznam.cz>
 */
class IfAllowedHrefMacro extends \Nette\Object implements \Nette\Latte\IMacro {
	
	/** @var \Nette\Latte\Compiler */
	protected $compiler;
	
	public static function install(\Nette\Latte\Compiler $compiler) {
		$compiler->addMacro('ifAllowedHref', new static($compiler));
		$compiler->addMacro('allowedHref', new static($compiler));
	}
	
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
	public function nodeOpened(\Nette\Latte\MacroNode $node) {
		$node->isEmpty = FALSE;
		$this->compiler->setContext(\Nette\Latte\Compiler::CONTEXT_DOUBLE_QUOTED);
		$res1 = $this->compile($node, array($this, 'macroHref'));
		$this->compiler->setContext(NULL);
		if (!$node->attrCode) {
			$node->attrCode = "<?php $res1 ?>";
		}

		$res2 = $this->compile($node, array('Permission\Checker\PermissionMacros', 'macroIfAllowedLink'));
		if (!$node->openingCode) {
			$node->openingCode = "<?php $res2 ?>";
		}
			
		return $res1 !== false && $res2 !== false;		
	}

	public function nodeClosed(\Nette\Latte\MacroNode $node) {
		$res = $this->compile($node, array('Permission\Checker\PermissionMacros', 'macroIfAllowedLinkEnd'));
		if (!$node->closingCode) {
			$node->closingCode = "<?php $res ?>";
		}		
	}
	
	
	
	public function macroHref(\Nette\Latte\MacroNode $node, \Nette\Latte\PhpWriter $writer) {
		return $writer->write(' ?> href="<?php echo %escape(' . ($node->name === 'plink' ? '$_presenter' : '$_control') . '->link(%node.word, %node.array?)) ?>"<?php ');
	}	
	
	
	private function compile(\Nette\Latte\MacroNode $node, $def)	{
		$node->tokenizer->reset();
		$writer = \Nette\Latte\PhpWriter::using($node, $this->compiler);
		if (is_string($def)) {
			return $writer->write($def);
		} else {
			return callback($def)->invoke($node, $writer);
		}
	}	
}