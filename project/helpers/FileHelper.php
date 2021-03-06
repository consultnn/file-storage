<?php

namespace app\helpers;

/**
 * Class FileHelper
 * @package app\helpers
 */
class FileHelper
{
    /**
     * @param $number
     * @param $fromBase
     * @param $toBase
     * @return mixed
     */
    public static function internalBaseConvert($number, $fromBase, $toBase)
    {
        return gmp_strval(gmp_init($number, $fromBase), $toBase);
    }

    /**
     * Split file name on path pieces
     *
     * @param string $name
     * @param int $count
     * @return string[]
     */
    public static function splitNameIntoParts($name, $count = 2)
    {
        static $lengthOfPiece = 2;
        $pieces = [];

        do {
            $pieces[] = substr($name, count($pieces) * $lengthOfPiece, $lengthOfPiece);
        } while (count($pieces) < $count);

        $pieces[] = substr($name, count($pieces) * $lengthOfPiece);

        return $pieces;
    }

    /**
     * @param string $filePath
     * @return string
     */
    public static function getExtension($filePath)
    {
        if ($mime = self::getMimeType($filePath)) {
            return self::getExtensionFromMime($mime);
        }

        $imageInfo = getimagesize($filePath);

        if (isset($imageInfo['mime'])) {
            $extension = explode('/', $imageInfo['mime'])[1];

            return $extension == 'jpeg' ? 'jpg' : $extension;
        }

        return false;
    }

    /**
     * @param string $mime
     * @return null|string
     */
    private static function getExtensionFromMime($mime)
    {
        if ($mime) {
            $mime = explode(';', $mime)[0];

            return explode('/', $mime)[1];
        }

        return null;
    }

    /**
     * @param $file
     * @return mixed|null
     */
    public static function getMimeType($file)
    {
        $info = finfo_open(FILEINFO_MIME_TYPE);

        if ($info) {
            $result = finfo_file($info, $file);
            finfo_close($info);

            if ($result !== false) {
                return $result;
            }
        }

        return static::getMimeTypeByExtension($file);
    }

    /**
     * @param string $file
     * @return null|string
     */
    public static function getMimeTypeByExtension($file)
    {
        $mimeTypes = \App::$instance->config['mime-types'];

        if (($ext = pathinfo($file, PATHINFO_EXTENSION)) !== '') {
            $ext = strtolower($ext);
            if (isset($mimeTypes[$ext])) {
                return $mimeTypes[$ext];
            }
        }

        return null;
    }

    /**
     * Make a path relative to the storage
     *
     * @param string $hash
     * @param string $project
     * @param string $extension
     * @return string
     */
    public static function makePath($hash, $project, $extension)
    {
        $nameParts = FileHelper::splitNameIntoParts($hash);

        $pathPrefix = $project . '/' . implode('/', $nameParts);
        
        return $pathPrefix . '.' . $extension;
    }

    /**
     * Make secure hash based on file path, params and download token
     *
     * @param string $filePath
     * @param array $params
     * @param string $downloadToken
     * @return string
     */
    public static function internalHash($filePath, $params, $downloadToken)
    {
        $hash = hash(
            'crc32',
            $downloadToken . $filePath . $params . $downloadToken
        );

        return str_pad(self::internalBaseConvert($hash, 16, 36), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check what hash from request equal to origin hash
     *
     * @param string $hash
     * @param string $fileName
     * @param array $params
     * @return bool
     */
    public static function availableHash($hash, $fileName, $params)
    {
        foreach (\App::$instance->config['downloadTokens'] as $token) {
            $newHash = FileHelper::internalHash($fileName, $params, $token);

            if ($newHash === $hash) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get real path by relative
     * @param string $webPath
     * @return string|boolean
     */
    public static function resolvePhysicalPath($webPath)
    {
        $storagePath = STORAGE_DIR . '/';

        if (is_file($storagePath.$webPath))
            return $storagePath.$webPath;

        $pathInfo = pathinfo($webPath);
        $symlinkPath = $storagePath . $pathInfo['dirname'].'/'.$pathInfo['filename'];

        if (is_link($symlinkPath))
            return readlink($symlinkPath);

        return false;
    }
}