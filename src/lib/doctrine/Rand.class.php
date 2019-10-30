<?php

    use Doctrine\ORM\Query\Lexer;
    use Doctrine\ORM\Query\AST\Functions\FunctionNode;

    /**
     * MySQL Rand function class
     *
     * Allows Doctrine 2.0 Query Language to execute a MySQL Rand function
     * You must boostrap this function in your ORM as a DQLFunction.
     * 
     * @link labs.ultravioletdesign.co.uk
     * @author Rob Squires <rob@ultravioletdesign.co.uk>
     * 
     */
    class Rand extends FunctionNode {

        /**
         * getSql - allows ORM  to inject a RAND() statement into an SQL string being constructed
         * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
         * @return void
         */
        public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
            return 'RAND()';
        }

        /**
         * parse - allows DQL to breakdown the DQL string into a processable structure
         * @param \Doctrine\ORM\Query\Parser $parser
         */
        public function parse(\Doctrine\ORM\Query\Parser $parser) {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        }

    }
    