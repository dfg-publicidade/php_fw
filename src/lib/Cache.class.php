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
     * Cache class
     */
    class Cache {

        /**
         * Create cache dirs
         */
        static function createDirs() {
            if (!file_exists(TMP_PATH)) {
                mkdir(TMP_PATH);
            }

            if (!file_exists(TMP_PATH . 'cache')) {
                mkdir(TMP_PATH . 'cache');
            }
        }

        /**
         * Get cached value
         * @param $fileName Cache file name
         * @return Cached value
         */
        static function get($fileName) {
            $fileName = TMP_PATH . 'cache' . DS . $fileName;
            if (file_exists($fileName)) {
                $handle   = fopen($fileName, 'rb');
                $variable = fread($handle, filesize($fileName));
                fclose($handle);
                return unserialize($variable);
            }
            else {
                return null;
            }
        }

        /**
         * Set chache value
         * @param $fileName Cache file name
         * @param $variable Variable to set
         */
        static function set($fileName, $variable) {
            $fileName = TMP_PATH . 'cache' . DS . $fileName;
            $handle   = fopen($fileName, 'a');
            fwrite($handle, serialize($variable));
            fclose($handle);
        }

    }
    