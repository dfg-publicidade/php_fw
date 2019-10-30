<?php

    /*
     * Copyright (C) 2017 DFG Studio.
     *
     * This library is free software; you can redistribute it and/or
     * modify it under the terms of the GNU Lesser General Public
     * License as published by the Free Software Foundation; either
     * version 2.1 of the License, or (at your option) any later version.
     *
     * This library is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
     * Lesser General Public License for more details.
     *
     * You should have received a copy of the GNU Lesser General Public
     * License along with this library; if not, write to the Free Software
     * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
     * MA 02110-1301  USA
     */

    use Doctrine\ORM\Query\Lexer;
    use Doctrine\ORM\Query\AST\Functions\FunctionNode;

    /**
     * MySQL YEAR function class
     *
     * Allows Doctrine 2.0 Query Language to execute a MySQL YEAR function
     * You must boostrap this function in your ORM as a DQLFunction.
     * 
     * YEAR(TIMESTAMP) : @link http://dev.mysql.com/doc/refman/5.5/en/date-and-time-functions.html#function_year
     */
    class Year extends FunctionNode {

        /**
         * holds the timestamp of the DATE_FORMAT DQL statement
         * @var mixed
         */
        protected $dateExpression;

        /**
         * getSql - allows ORM  to inject a YEAR() statement into an SQL string being constructed
         * @param \Doctrine\ORM\Query\SqlWalker $sqlWalker
         * @return void
         */
        public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker) {
            return 'YEAR(' .
                    $sqlWalker->walkArithmeticExpression($this->dateExpression) .
                    ')';
        }

        /**
         * parse - allows DQL to breakdown the DQL string into a processable structure
         * @param \Doctrine\ORM\Query\Parser $parser
         */
        public function parse(\Doctrine\ORM\Query\Parser $parser) {
            $parser->match(Lexer::T_IDENTIFIER);
            $parser->match(Lexer::T_OPEN_PARENTHESIS);
            $this->dateExpression = $parser->ArithmeticExpression();
            $parser->match(Lexer::T_CLOSE_PARENTHESIS);
        }

    }
    