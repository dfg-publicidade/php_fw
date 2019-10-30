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

    define('APP_PATH', ROOT . DS . 'app' . DS);
    define('CFG_PATH', ROOT . DS . 'config' . DS);
    define('LIB_PATH', __DIR__ . DS . 'lib' . DS);
    define('RES_PATH', ROOT . DS . 'resources' . DS);
    define('TMP_PATH', ROOT . DS . 'tmp' . DS);
    define('VND_PATH', ROOT . DS . 'vendor' . DS);

    $url = isset($_GET['url']) ? $_GET['url'] : '';

    require_once(VND_PATH . 'autoload.php');
    require_once(CFG_PATH . 'config.php');
    require_once(CFG_PATH . 'routing.php');
    require_once(__DIR__ . DS . 'doctrine.php');
    require_once(__DIR__ . DS . 'autoloader.php');

    Cache::createDirs();

    session_start();

    $dfgfw = new DFGFW();

    $dfgfw->gzipOutput() || ob_start("ob_gzhandler");
    $dfgfw->setReporting();
    $dfgfw->removeMagicQuotes();
    $dfgfw->exec();
    