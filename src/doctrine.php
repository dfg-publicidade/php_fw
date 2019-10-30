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

    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;

    if (!DB_DISABLED) {
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), true);

        $config->addCustomDatetimeFunction('DATE_FORMAT', "DateFormat");
        $config->addCustomNumericFunction('RAND', "Rand");
        $config->addCustomDatetimeFunction('YEAR', "Year");

        $conn = array(
            'dbname'        => DB_NAME,
            'user'          => DB_USER,
            'password'      => DB_PASSWORD,
            'host'          => DB_HOST,
            'driver'        => DB_DRIVER,
            'charset'       => DB_CHARSET,
            'driverOptions' => unserialize(DB_OPTIONS)
        );

        $entityManager = EntityManager::create($conn, $config);
    }
    