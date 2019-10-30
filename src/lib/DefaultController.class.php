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
     * Default controller class
     */
    class DefaultController {

        /**
         * Entity manager
         */
        private $entityManager;

        /**
         * Controller
         */
        private $_controller;

        /**
         * Action
         */
        private $_action;

        /**
         * Query string
         */
        private $_queryString;

        /**
         * Template
         */
        private $_template;

        /**
         * Template file
         */
        protected $template_file;

        /**
         * Render flag
         */
        protected $render = 1;

        /**
         * Construct
         * @param $entityManager Entity manager
         * @param $controller Controller
         * @param $action Action
         * @param $queryString Query string
         */
        function __construct($entityManager, $controller, $action, $queryString) {
            $this->entityManager = $entityManager;
            $this->_controller   = ucfirst($controller);
            $this->_action       = $action;
            $this->_queryString  = $queryString;
            $this->_template     = new Template($controller, $action, $entityManager);
        }

        /**
         * Get entity manager
         * @return Entity Manager
         */
        function getEntityManager() {
            return $this->entityManager;
        }

        /**
         * Get action
         * @return Action
         */
        function getAction() {
            return $this->_action;
        }

        /**
         * Get render flag
         * @return Render flag
         */
        function getRender() {
            return $this->render;
        }

        /**
         * Set page variable
         * @param $name Variable name
         * @param $value Variable value
         */
        function set($name, $value) {
            $this->_template->set($name, $value);
        }

        /**
         * Get page variable
         * @param $name Variable name
         * @return Variable value
         */
        function get($name) {
            return $this->_template->get($name);
        }

        /**
         * Get request parameter
         * @param $param Parameter name
         * @return Parameter value
         */
        function getParameter($param) {
            if (isset($_POST[$param])) {
                return htmlspecialchars($_POST[$param], ENT_QUOTES, 'UTF-8');
            }
            else if (isset($_GET[$param])) {
                return htmlspecialchars($_GET[$param], ENT_QUOTES, 'UTF-8');
            }

            return '';
        }

        /**
         * Change current action
         * @param $action new Action
         */
        function redirect($action) {
            $this->_action = $action;
            $this->_template->setAction($action);
        }

        /**
         * Redirect request to another action
         * @param $action new Action
         */
        function redirectAction($action, $params = array()) {
            call_user_func_array(array($this, $action), $params);
            $this->_template->setAction($action);
        }

        /**
         * Set template file
         * @param $template_file Template file
         */
        function setTemplate($template_file) {
            $this->template_file = $template_file;
        }

        /**
         * Set render flag
         * @param $render Render flag
         */
        function setRender($render) {
            $this->render = $render;
        }

        /**
         * Finalizes controller
         */
        function __destruct() {
            if ($this->render) {
                $this->_template->render($this->template_file);
            }
        }

    }
    