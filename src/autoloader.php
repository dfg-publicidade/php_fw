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

    spl_autoload_register("autoload");

    /**
     * Autoload method
     * @param $className Class name
     */
    function autoload($className) {
        if (file_exists(LIB_PATH . ucfirst($className) . '.class.php')) {
            require_once(LIB_PATH . ucfirst($className) . '.class.php');
        }
        else if (file_exists(LIB_PATH . 'doctrine' . DS . ucfirst($className) . '.class.php')) {
            require_once(LIB_PATH . 'doctrine' . DS . ucfirst($className) . '.class.php');
        }
        else if (file_exists(APP_PATH . 'controllers' . DS . ucfirst($className) . '.class.php')) {
            require_once(APP_PATH . 'controllers' . DS . ucfirst($className) . '.class.php');
        }
        else if (file_exists(APP_PATH . 'models' . DS . ucfirst($className) . '.php')) {
            require_once(APP_PATH . 'models' . DS . ucfirst($className) . '.php');
        }
        else if (defined('ENTITY_DIR') && file_exists(ENTITY_DIR . ucfirst($className) . '.php')) {
            require_once(ENTITY_DIR . ucfirst($className) . '.php');
        }
        else if (file_exists(APP_PATH . 'services' . DS . ucfirst($className) . '.class.php')) {
            require_once(APP_PATH . 'services' . DS . ucfirst($className) . '.class.php');
        }
        else if (defined('SERVICE_DIR') && file_exists(SERVICE_DIR . ucfirst($className) . '.class.php')) {
            require_once(SERVICE_DIR . ucfirst($className) . '.class.php');
        }
        else if (file_exists(APP_PATH . 'controllers' . DS . 'validators' . DS . ucfirst($className) . '.php')) {
            require_once(APP_PATH . 'controllers' . DS . 'validators' . DS . ucfirst($className) . '.php');
        }
    }
    