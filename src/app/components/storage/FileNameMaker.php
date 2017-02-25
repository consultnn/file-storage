<?php

declare(strict_types=1);

namespace app\components\storage;

use League\Flysystem\Util;
use League\Flysystem\Util\MimeType;

/**
 * Class FileNameMaker
 * @package app\components\storage
 */
class FileNameMaker
{
    /**
     * File extensions map for browsers
     * @var array
     */
    protected static $webExtensionsMap = [
        'jpe' => 'jpeg'
    ];

    /**
     * Generate filename for upload
     * @param string $file
     * @param int $length
     * @return string
     */
    public function makeName($file, $length = 13)
    {
        $sha = sha1_file($file);

        $name = substr($sha, 0, $length);

        if ($extension = $this->getExtension($file)) {
            $name = $name . '.' . $extension;
        }

        return $name;
    }

    public function getExtension($file)
    {
        static $mimeTypeToExtensionMap;

        if (!$mimeTypeToExtensionMap) {
            $mimeTypeToExtensionMap = array_flip(MimeType::getExtensionToMimeTypeMap());
        }

        $mimeType = Util::guessMimeType($file, file_get_contents($file));

        if (array_key_exists($mimeType, $mimeTypeToExtensionMap)) {
            $extension =  $mimeTypeToExtensionMap[$mimeType];
        } else {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        }

        if (array_key_exists($extension, self::$webExtensionsMap)) {
            $extension = self::$webExtensionsMap[$extension];
        }

        return $extension;
    }
}
