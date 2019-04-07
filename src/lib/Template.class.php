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
     * Page render template
     */
    class Template {

        /**
         * Page variables
         */
        protected $variables = array();

        /**
         * Controller
         */
        protected $_controller;

        /**
         * Action
         */
        protected $_action;

        /**
         * Entity Manager
         */
        private $entityManager;

        /**
         * HTML tools
         */
        private $html;

        /**
         * Construct
         * @param $controller Controller
         * @param $action Action
         * @param $entityManager Entity Manager
         */
        function __construct($controller, $action, $entityManager) {
            $this->_controller   = $controller;
            $this->_action       = $action;
            $this->entityManager = $entityManager;
            $this->html          = new HTML();
        }

        /**
         * Set page variable
         * @param $name Variable name
         * @param $value Variable value
         */
        function set($name, $value) {
            $this->variables[$name] = $value;
        }

        /**
         * Get page variable
         * @param $name Variable name
         * @return Variable value
         */
        function get($name) {
            if (isset($this->variables[$name]))
                return $this->variables[$name];

            return null;
        }

        /**
         * Set action
         * @param type $action
         */
        function setAction($action) {
            $this->_action = $action;
        }

        /**
         * Get entity manager
         * @return Entity Manager
         */
        function getEntityManager() {
            return $this->entityManager;
        }

        /**
         * Require file from template repository
         * @param $file File path
         */
        function requiref($file) {
            $html     = $this->html;
            $template = $this;
            extract($this->variables);

            require(APP_PATH . 'templates' . DS . $file);
        }

        /**
         * Render page from template
         * @param $template_file Template file path
         */
        function render($template_file = '') {
            $html     = $this->html;
            $template = $this;
            extract($this->variables);

            if (isset($template_file) && !empty($template_file) && file_exists(APP_PATH . 'templates' . DS . $template_file . '.php')) {
                require_once(APP_PATH . 'templates' . DS . $template_file . '.php');
            }
            else if (file_exists(APP_PATH . 'views' . DS . lcfirst($this->_controller) . DS . $this->_action . '.php')) {
                require(APP_PATH . 'views' . DS . lcfirst($this->_controller) . DS . $this->_action . '.php');
            }
        }

    }
    