<?php

declare(strict_types=1);

namespace Doctrine\RST\Directives;

use Doctrine\RST\Nodes\CodeNode;
use Doctrine\RST\Nodes\Node;
use Doctrine\RST\Parser;

/**
 * A directive that parses the sub block and call the processSub that can
 * be overloaded, like :
 *
 * .. sub-directive::
 *      Some block of code
 *
 *      You can imagine anything here, like adding *emphasis*, lists or
 *      titles
 */
abstract class SubDirective extends Directive
{
    /**
     * @param string[] $options
     */
    final public function process(
        Parser $parser,
        ?Node $node,
        string $variable,
        string $data,
        array $options
    ) : void {
        $subParser = $parser->getSubParser();

        if ($node instanceof CodeNode) {
            $document = $subParser->parseLocal($node->getValue());
        } else {
            $document = $node;
        }

        $newNode = $this->processSub($parser, $document, $variable, $data, $options);

        if ($newNode === null) {
            return;
        }

        if ($variable !== '') {
            $parser->getEnvironment()->setVariable($variable, $newNode);
        } else {
            $parser->getDocument()->addNode($newNode);
        }
    }

    /**
     * @param string[] $options
     */
    public function processSub(
        Parser $parser,
        ?Node $document,
        string $variable,
        string $data,
        array $options
    ) : ?Node {
        return null;
    }

    public function wantCode() : bool
    {
        return true;
    }
}
