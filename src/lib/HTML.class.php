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
     * HTML utilitary class
     */
    class HTML {

        private $collectedJs = '';

        /**
         * Javascript file include shortcut
         * @param $fileName File name
         */
        function includeJs($fileName) {
            echo '<script src="' . CONTEXT_PATH . 'res/js/' . $fileName . '.js' . (VERSION != null ? '?version=' . VERSION : '') . '"></script>';
        }

        /**
         * Css file include shortcut
         * @param $fileName File name
         */
        function includeCss($fileName, $media = '') {
            echo '<link rel="stylesheet" type="text/css" href="' . CONTEXT_PATH . 'res/css/' . $fileName . '.css' . (VERSION != null ? '?version=' . VERSION : '') . '" media="' . $media . '" />';
        }

        /**
         * Start to collect JS data
         */
        function startCollectJs() {
            ob_start();
        }

        /**
         * Stop and cache collected JS data
         */
        function stopCollectJs() {
            $this->collectedJs = ob_get_clean();
        }

        /**
         * Print collected JS data
         */
        function printCollectedJs() {
            echo $this->collectedJs;
            $this->collectedJs = '';
        }

    }
    