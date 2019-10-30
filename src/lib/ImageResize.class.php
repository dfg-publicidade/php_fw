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
     * Image resize tool
     */
    class ImageResize {

        /**
         * Resize an image (jpg / png)
         * @param $sourcePath Source path
         * @param $ext Source extension
         * @param $targetPath Target path
         * @param $width Target width
         * @param $height Target height
         */
        public static function resizeImage($sourcePath, $ext, $targetPath, $width, $height) {
            list($orig_width, $orig_height) = getimagesize($sourcePath);

            if ($width < 0 || $height < 0) {
                if ($width >= 0 && $height < 0) {
                    $height = $orig_height * $width / $orig_width;
                }
                else if ($width < 0 && $height >= 0) {
                    $width = $orig_width * $height / $orig_height;
                }
                else {
                    $width  = $orig_width;
                    $height = $orig_height;
                }
            }

            $target = imagecreatetruecolor($width, $height);

            $source;
            if ($ext != 'png') {
                $source = imagecreatefromjpeg($sourcePath);
            }
            else {
                $source      = imagecreatefrompng($sourcePath);
                $transparent = imagefill($target, 0, 0, imagecolorallocatealpha($target, 255, 255, 255, 127));
                imagealphablending($target, false);
                imagesavealpha($target, true);
            }

            imagecopyresampled($target, $source, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

            if ($ext != 'png') {
                imagejpeg($target, $targetPath);
            }
            else {
                imagepng($target, $targetPath);
            }
        }

        /**
         * Crop and resize an image (jpg / png)
         * @param $sourcePath Source path
         * @param $ext Source extension
         * @param $targetPath Target path
         * @param $width Target width
         * @param $height Target height
         */
        public static function cropResizeImage($sourcePath, $ext, $targetPath, $width, $height) {
            list($orig_width, $orig_height) = getimagesize($sourcePath);

            if ($width < 0 || $height < 0) {
                if ($width >= 0 && $height < 0) {
                    $height = $width;
                }
                else if ($width < 0 && $height >= 0) {
                    $width = $height;
                }
                else {
                    $width  = $orig_width;
                    $height = $orig_height;
                }
            }

            $target = imagecreatetruecolor($width, $height);

            $source;
            if ($ext != 'png') {
                $source = imagecreatefromjpeg($sourcePath);
            }
            else {
                $source      = imagecreatefrompng($sourcePath);
                $transparent = imagefill($target, 0, 0, imagecolorallocatealpha($target, 255, 255, 255, 127));
                imagealphablending($target, false);
                imagesavealpha($target, true);
            }

            if ($orig_width > $orig_height) {
                $prev_width = $orig_width * $height / $orig_height;
                $adjust     = ($prev_width - $width) * ($orig_width / $width) / 2;

                imagecopyresampled($target, $source, 0, 0, $adjust, 0, $prev_width, $height, $orig_width, $orig_height);
            }
            else {
                $prev_height = $orig_height * $width / $orig_width;
                $adjust      = ($prev_height - $height) * ($orig_height / $height) / 2;

                imagecopyresampled($target, $source, 0, 0, 0, $adjust, $width, $prev_height, $orig_width, $orig_height);
            }

            if ($ext != 'png') {
                imagejpeg($target, $targetPath);
            }
            else {
                imagepng($target, $targetPath);
            }
        }

    }
    