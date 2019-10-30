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

    /**
     * General utilitary class
     */
    class Util {

        /**
         * Sanitize a string
         * @param $str String to sanitize
         * @return clean string
         */
        static function sanitize($str) {
            return mysql_real_escape_string($str);
        }

        /**
         * Parse a string to Ascii-only format
         * @param $str Original string
         * @return Ascii-only string
         */
        static function toAscii($str) {
            $delimiter = '-';

            $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
            $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
            $clean = strtolower(trim($clean, '-'));
            $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

            return $clean;
        }

        /**
         * Integer cleaning
         * @param $var Value to test
         * @param $default Value if invalid
         */
        static function cleanInt(&$var, $default) {
            $i = (int) $var;

            if ($i <= 0) {
                $var = $default;
            }
        }

        /**
         * Parse a string to float
         * @param $value Value to parse
         * @return Float value
         */
        static function parseFloat($value) {
            return $value != null ? str_replace(',', '.', str_replace('.', '', $value)) : null;
        }
        
        /**
         * Parse a string to date
         * @param $value Value to parse
         * @return Date value
         */
        static function parseDate($value) {
            return $value != null ? date_create_from_format('d/m/Y', $value) : null;
        }

        /**
         * Parse a string to datetime
         * @param $value Value to parse
         * @return DateTime value
         */
        static function parseDateTime($value) {
            return $value != null ? date_create_from_format('d/m/Y H:i', $value) : null;
        }

        /**
         * Test if mail is valid
         * @param $email E-mail
         * @return true if valid / false if not
         */
        static function isMail($email) {
            $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
            if (preg_match($er, $email)) {
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Parse a float value to pt-BR format
         * @param $value Value to parse
         * @return Parsed valeu
         */
        static function isBrPhone($celular) {
            $er = '/^(\(\d{2}\) )?9\d{4}-\d{4}$/';
            if (preg_match($er, $celular)) {
                return true;
            }
            else {
                return false;
            }
        }

        /**
         * Get Youtube code from URL
         * @param $url URL
         * @return Youtube code
         */
        static function getYoutube($url) {
            parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
            return $my_array_of_vars['v'];
        }

        /**
         * Pagination
         * @param type $args Arguments
         * @param type $total Total of items
         * @param type $limit Limit of itens per page
         * @param type $pag Current page
         * @param type $maxPags Max page links
         * @param type $shortPagination Short pagination
         * @return string Pagination code
         */
        static function paginate($args = '', $total = 0, $limit = 20, $pag = 1, $maxPags = 5, $shortPagination = false) {
            $pagination = '';

            $pag = $pag + 1;

            $cntPags = ceil($total / $limit);

            if ($cntPags > 1) {
                $pagination .= "<ul class=\"pagination pagination no-margin pull-left\">";

                if ($shortPagination === true) {
                    $pagination .= "<li><a href=\"" . $args . "\"><i class=\"fa fa-chevron-left\"></i>&nbsp;</a></li>\n";
                }

                $firstPag;
                $lastPag;
                $div = floor($maxPags / 2);

                if ($shortPagination === true) {
                    if ($cntPags > $maxPags) {
                        $firstPag = ($pag - $div);

                        if ($firstPag <= 0) {
                            $firstPag += 1 - $firstPag;
                        }

                        $lastPag = $firstPag + $maxPags - 1;

                        if ($lastPag > $cntPags) {
                            $firstPag -= $lastPag - $cntPags;
                            $lastPag  = $firstPag + $maxPags - 1;
                        }
                    }
                    else {
                        $firstPag = 1;
                        $lastPag  = $cntPags;
                    }
                }
                else {
                    $firstPag = 1;
                    $lastPag  = $cntPags;
                }

                for ($i = $firstPag; $i <= $lastPag; $i++) {
                    if ($pag == $i) {
                        $pagination .= "<li class=\"active\"><a href=\"" . $args . "\">" . $i . "</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $args . $i . "\">" . $i . "</a></li>\n";
                    }
                }

                if ($shortPagination === true) {
                    $pagination .= "<li><a href=\"" . $args . ceil($total / $limit) . "\"><i class=\"fa fa-chevron-right\"></i>&nbsp;</a></li>\n";
                }

                $pagination .= "</ul>";
            }

            return $pagination;
        }

    }
    