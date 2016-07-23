<?php

namespace app\workers;

use app\helpers\FileHelper;
use app\interfaces\FileWorker;
use Imagine\Filter\Transformation;
use Imagine\Gmagick\Imagine;
use Imagine\Image\Box;

class Image implements FileWorker
{
    const DEFAULT_QUALITY = 85;

    /**
     * Make operation to image
     *
     * Available operations:
     * - w - generate thumbnail with width equal `w` (default - original)
     * - h - generate thumbnail with height equal `h` (default - original)
     * - q - quality of thumbnail (default - 85%)
     *
     * @param $path
     * @param $params
     */
    public function makeFile($path, $params = [])
    {
        $imagine = new Imagine;
        $transformation = new Transformation();
        $image = $imagine->open($path);
        $options = [];

        $format = FileHelper::getExtension($path);

        // Thumbnail
        if (!empty($params['w']) || !empty($params['h'])) {
            $box = new Box(
                (int) ($params['w'] ?? $params['h']),
                (int) ($params['h'] ?? $params['w'])
            );

            $transformation->thumbnail($box);
        }

        $quality = $params['q'] ?? self::DEFAULT_QUALITY;

        $options = array_merge($options, $this->getQualityOptions($format, $quality));

        $transformation->apply($image)->show($format, $options);
    }

    private function getQualityOptions($format, $quality)
    {
        $options = [];

        switch ($format) {
            case 'png':
                $options['png_compression_filter'] = ceil($quality / 10);
                break;
            case 'jpg':
            case 'jpeg':
            case 'pjpeg':
                $options['jpeg_quality'] = $quality;
                break;
        }

        return $options;
    }
}