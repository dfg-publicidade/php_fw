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
     * DFG Framework main class
     */
    class DFGFW {

        /**
         * Set reporting type
         */
        function setReporting() {
            if (DEVELOPMENT_ENVIRONMENT == true) {
                error_reporting(E_ALL);
                ini_set('display_errors', 'On');
            }
            else {
                error_reporting(E_ALL);
                ini_set('display_errors', 'Off');
                ini_set('log_errors', 'On');
                ini_set('error_log', TMP_PATH . 'logs' . DS . 'error.log');
            }
        }

        /**
         * Removing parameters slashes
         * @param $value Parameter values
         * @return cleaned parameters
         */
        function stripSlashesDeep($value) {
            $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
            return $value;
        }

        /**
         * Removing magic quotes
         */
        function removeMagicQuotes() {
            if (get_magic_quotes_gpc()) {
                $_GET    = stripSlashesDeep($_GET);
                $_POST   = stripSlashesDeep($_POST);
                $_COOKIE = stripSlashesDeep($_COOKIE);
            }
        }

        /**
         * Performs an action
         * @param $controller Controller
         * @param $action Action
         * @param $queryString Query string
         * @param $render Render
         * @return Action result
         */
        function performAction($controller, $action, $queryString = null, $render = 0) {
            $controllerName   = ucfirst($controller) . 'Controller';
            $dispatch         = new $controllerName($controller, $action);
            $dispatch->render = $render;

            return call_user_func_array(array($dispatch, $action), $queryString);
        }

        /**
         * Routes the request
         * @param $url URL to route
         * @return Routed URL
         */
        function routeURL($url) {
            global $routing;

            foreach ($routing as $pattern => $result) {
                if (preg_match($pattern, $url)) {
                    if (is_array($result)) {
                        if (isset($result[$_SERVER['REQUEST_METHOD']])) {
                            return preg_replace($pattern, $result[$_SERVER['REQUEST_METHOD']], $url);
                        }
                    }
                    else {
                        return preg_replace($pattern, $result, $url);
                    }
                }
            }

            return ($url);
        }

        /**
         * Main execution
         */
        function exec() {
            global $url;
            global $default;
            global $entityManager;

            $queryString = array();

            if (isset($url) && !empty($url)) {
                $url        = $this->routeURL($url);
                $urlArray   = array();
                $urlArray   = explode("/", $url);
                $controller = $urlArray[0];
                array_shift($urlArray);
                if (isset($urlArray[0])) {
                    $action = $urlArray[0];
                    array_shift($urlArray);
                }
                else {
                    $action = 'main'; // Default Action
                }
                $queryString = $urlArray;
            }
            else {
                $controller = $default['controller'];
                $action     = $default['action'];
            }

            $controllerName = ucfirst($controller) . 'Controller';

            if (class_exists($controllerName)) {
                $dispatch = new $controllerName($entityManager, $controller, $action, $queryString);

                if ((int) method_exists($controllerName, $action)) {
                    $continue = true;

                    if ((int) method_exists($controllerName, "before")) {
                        $continue = call_user_func_array(array($dispatch, "before"), $queryString);
                    }

                    if ($continue) {
                        if ((int) method_exists($controllerName, "before" . ucfirst($action))) {
                            $continue = call_user_func_array(array($dispatch, "before" . ucfirst($action)), $queryString);
                        }

                        if ($continue) {
                            $continue = call_user_func_array(array($dispatch, $action), $queryString);

                            if ($continue) {
                                if ((int) method_exists($controllerName, "after" . ucfirst($action))) {
                                    $continue = call_user_func_array(array($dispatch, "after" . ucfirst($action)), $queryString);
                                }

                                if ($continue) {
                                    if ((int) method_exists($controllerName, "after")) {
                                        call_user_func_array(array($dispatch, "after"), $queryString);
                                    }
                                }
                            }
                        }
                        else {
                            if ($dispatch->getRender() != 0) {
                                $dispatch->setTemplate('');
                                $dispatch->setRender(0);
                                require_once(ROOT . DS . 'webapp/error.php');
                            }
                        }
                    }
                    else {
                        if ($dispatch->getRender() != 0) {
                            $dispatch->setTemplate('');
                            $dispatch->setRender(0);
                            require_once(ROOT . DS . 'webapp/error.php');
                        }
                    }
                }
            }
            else {
                $error_id = '404';
                http_response_code($error_id);
                require_once(ROOT . DS . 'webapp/error.php');
            }
        }

        /**
         * GZIP compression
         * @return true if compression is enabled / false if not
         */
        function gzipOutput() {
            $ua = $_SERVER['HTTP_USER_AGENT'];

            if (0 !== strpos($ua, 'Mozilla/4.0 (compatible; MSIE ') || false !== strpos($ua, 'Opera')) {
                return false;
            }

            $version = (float) substr($ua, 30);
            return ($version < 6 || ($version == 6 && false === strpos($ua, 'SV1')));
        }

    }
    