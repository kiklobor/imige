<?php

/**
 * This file is part of the martijnvdb/php-image-resize library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Martijn van den Bosch <martijn_van_den_bosch@hotmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace Martijnvdb\ImageResize;

/**
 * ImageResize provides methods for working resizing and exporting images.
 */
class ImageResize
{
    /**
     * The path to the source image.
     * @var string
     */
    private $source_path;

    /**
     * The extension to the source image.
     * @var string
     */
    private $source_extension;

    /**
     * The MIME content type to the source image.
     * @var string
     */
    private $source_mimetype;

    /**
     * The created image of the source image.
     */
    private $source_image;

    /**
     * The width of the source image.
     * @var int
     */
    private $source_width;

    /**
     * The height of the source image.
     * @var int
     */
    private $source_height;

    /**
     * The ratio of the source image.
     * @var float
     */
    private $source_ratio;

    /**
     * The path to the target image.
     * @var string
     */
    private $target_path;

    /**
     * The width of the target image.
     * @var int
     */
    private $target_width;

    /**
     * The height of the target image.
     * @var int
     */
    private $target_height;

    /**
     * The quality of the target image.
     * @var int
     */
    private $target_quality = 60;

    /**
     * If the ratio of the target image should be fixed.
     * @var bool
     */
    private $target_fixed_ratio = true;

    /**
     * The MIME content type of the target image.
     * @var string
     */
    private $target_mimetype = null;

    /**
     * The cached images.
     * @var array
     */
    private static $cached_images = [];

    /**
     * Create a new instance of the ImageResize class.
     * 
     * @param  string $path
     */
    public function __construct(string $path)
    {
        $this->source_path = $path;
    }

    /**
     * Get the pathinfo of the source image.
     * 
     * @return array
     */
    private function getSourceInfo(): array
    {
        list(
            $this->source_dirname,
            $this->source_basename,
            $this->source_extension,
            $this->source_filename
        ) = pathinfo($this->source_path);

        return [
            $this->source_dirname,
            $this->source_basename,
            $this->source_extension,
            $this->source_filename
        ];
    }

    /**
     * Get the MIME content type of the source image.
     * 
     * @return string
     */
    public function getSourceMimeType(): string
    {
        if (!isset($this->source_mimetype)) {
            $this->source_mimetype = mime_content_type($this->source_path);
        }

        return $this->source_mimetype;
    }

    /**
     * Create an image of the source path.
     * 
     */
    private function createSourceImage()
    {
        if(self::getCachedImage($this->source_path)) {
            return self::getCachedImage($this->source_path);
        }

        if (in_array($this->getSourceMimeType(), ['image/jpg', 'image/jpeg'])) {
            $this->source_image = imagecreatefromjpeg($this->source_path);
        } else if ($this->getSourceMimeType() === 'image/png') {
            $this->source_image = imagecreatefrompng($this->source_path);
        } else if ($this->getSourceMimeType() === 'image/gif') {
            $this->source_image = imagecreatefromgif($this->source_path);
        } else if($this->getSourceMimeType() === 'image/webp') {
            $this->source_image = imagecreatefromwebp($this->source_path);
        }

        self::addImageToCache($this->source_path, $this->source_image);

        return $this->source_image;
    }

    /**
     * Select an image path.
     * 
     * @return self
     */
    public static function get(string $path): self
    {
        $instance = new self($path);
        $instance->createSourceImage();
        return $instance;
    }

    /**
     * Get the path of the source image.
     * 
     * @return self
     */
    public function getSourcePath(): string
    {
        return $this->source_path;
    }

    /**
     * Get the dimensions of the source image.
     * 
     * @return self
     */
    private function getSourceDimensions(): array
    {
        if (!isset($this->source_width) && !isset($this->source_height)) {
            list($this->source_width, $this->source_height) = getimagesize($this->getSourcePath());
        }

        return [$this->source_width, $this->source_height];
    }

    /**
     * Get the width of the source image.
     * 
     * @return self
     */
    public function getSourceWidth(): int
    {
        list($width, $height) = $this->getSourceDimensions();
        return $width;
    }

    /**
     * Get the heigth of the source image.
     * 
     * @return self
     */
    public function getSourceHeight(): int
    {
        list($width, $height) = $this->getSourceDimensions();
        return $height;
    }

    /**
     * Get the ratio of the source image.
     * 
     * @return float
     */
    public function getSourceRatio(): float
    {
        if (!isset($this->source_ratio)) {
            $this->source_ratio = $this->getSourceWidth() / $this->getSourceHeight();
        }

        return $this->source_ratio;
    }

    /**
     * Set the width of the target image.
     * 
     * @return self
     */
    public function setWidth(int $width): self
    {
        $this->target_width = $width;

        return $this;
    }

    /**
     * Set the height of the target image.
     * 
     * @return self
     */
    public function setHeight(int $height): self
    {
        $this->target_height = $height;

        return $this;
    }

    /**
     * Set the quality of the target image.
     * 
     * @return self
     */
    public function setQuality(int $quality): self
    {
        $this->target_quality = $quality;
        return $this;
    }

    /**
     * Use a fixed ratio for the target image.
     * 
     * @return self
     */
    public function fixedRatio(bool $fixed_ratio = true): self
    {
        $this->target_fixed_ratio = $fixed_ratio;
        return $this;
    }

    /**
     * Ignore the ratio of the target image.
     * 
     * @return self
     */
    public function ignoreRatio(bool $ignore_ratio = true): self
    {
        $this->target_fixed_ratio = !$ignore_ratio;
        return $this;
    }

    /**
     * Get the dimensions of the target image.
     * 
     * @return int
     */
    public function getTargetDimensions(): array
    {
        if(!isset($this->target_width) && !isset($this->target_height)) {
            $this->target_width = $this->getSourceWidth();
            $this->target_height = $this->getSourceHeight();

        } else {
            if($this->target_fixed_ratio) {
                if (isset($this->target_height) && !isset($this->target_width)) {
                    $this->target_width = $this->target_height * $this->getSourceRatio();

                } else if (isset($this->target_width) && !isset($this->target_height)) {
                    $this->target_height = $this->target_width / $this->getSourceRatio();

                } else {
                    if($this->target_width > $this->target_height * $this->getSourceRatio()) {
                        $this->target_width = $this->target_height * $this->getSourceRatio();

                    } else {
                        $this->target_height = $this->target_width / $this->getSourceRatio();
                    }
                }

            } else {
                if (isset($this->target_height) && !isset($this->target_width)) {
                    $this->target_width = $this->target_height * $this->getSourceRatio();

                } else if (isset($this->target_width) && !isset($this->target_height)) {
                    $this->target_height = $this->target_width / $this->getSourceRatio();
                }
            }
        }

        return [
            $this->target_width,
            $this->target_height
        ];
    }

    /**
     * Get the width of the target image.
     * 
     * @return int
     */
    public function getTargetWidth(): int
    {
        list($this->target_width, $this->target_height) = $this->getTargetDimensions();

        return $this->target_width;
    }

    /**
     * Get the height of the target image.
     * 
     * @return int
     */
    public function getTargetHeight(): int
    {
        list($this->target_width, $this->target_height) = $this->getTargetDimensions();

        return $this->target_height;
    }

    /**
     * Get the quality of the target image.
     * 
     * @return int
     */
    public function getTargetQuality(): int
    {
        return $this->target_quality;
    }

    /**
     * Get the path of the target image.
     * 
     * @return string
     */
    public function getTargetPath(): string
    {
        return $this->target_path;
    }

    /**
     * Set the path of the target image.
     * 
     * @param string $path
     */
    public function setTargetPath(string $path): self
    {
        $this->target_path = $path;

        return $this;
    }

    /**
     * Set the MIME type of the target image.
     * 
     * @param string $path
     * @return self
     */
    public function setType(string $type): self
    {
        switch($type) {
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/gif':
            case 'image/png':
            case 'image/webp':
                break;

            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'png':
            case 'webp':
                $type = "image/$type";
                break;

            default:
                $type = $this->getSourceMimeType();
        }

        $this->target_mimetype = $type;

        return $this;
    }

    /**
     * Get the MIME type of the target image.
     * 
     * @return string
     */
    private function getTargetType(): string
    {
        if(!isset($this->target_mimetype)) {
            $pathinfo = pathinfo($this->getTargetPath());
            $this->setType($pathinfo['extension']);
        }

        return $this->target_mimetype;
    }

    /**
     * Create the target image.
     * 
     */
    public function createTargetImage()
    {
        $this->target_image = imagecreatetruecolor($this->getTargetWidth(), $this->getTargetHeight());
        imagecopyresampled($this->target_image, $this->createSourceImage(), 0, 0, 0, 0, $this->getTargetWidth(), $this->getTargetHeight(), $this->getSourceWidth(), $this->getSourceHeight());

        return $this->target_image;
    }

    /**
     * Add an image to the ImageResize cache.
     * 
     * @param string $path
     * @param $image
     */
    public static function addImageToCache(string $path, $image): void
    {
        // жрёт память пока комментирую.
        //self::$cached_images[$path] = $image;
    }

    /**
     * Remove image from ImageResize cache.
     * 
     * @param string $path
     */
    public static function removeCachedImage(string $path): void
    {
        /*
        self::$cached_images = array_filter(self::$cached_images, function($value, $key) use ($path) {
            return $key !== $path;
        }, ARRAY_FILTER_USE_BOTH);
        /**/
        unset(self::$cached_images[$path]);
        if (array_key_exists($path, self::$cached_images)) {
          self::$cached_images[$path] = null;
          unset(self::$cached_images[$path]);
          //self::$cached_images = array_filter(self::$cached_images);
        }
    }

    /**
     * Get an image from the ImageResize cache.
     * 
     * @param string $path
     */
    public static function getCachedImage(string $path)
    {
        if(isset(self::$cached_images[$path])) {
            return self::$cached_images[$path];
        }

        return null;
    }

    /**
     * Create all missing dirs.
     * 
     * @return self
     */
    public function createMissingDirs(): self
    {
        $dirname = dirname($this->getTargetPath());

        if(!file_exists($dirname)) {
            mkdir($dirname, 0755, true);
        }

        return $this;
    }

    /**
     * Export the image using the given settings.
     * 
     * @return self
     */
    public function export(string $path): self
    {
        $this->setTargetPath($path);
        $this->createMissingDirs();

        if (in_array($this->getTargetType(), ['image/jpg', 'image/jpeg'])) {
            imagejpeg($this->createTargetImage(), $this->getTargetPath(), $this->getTargetQuality());
        } else if ($this->getTargetType() === 'image/png') {
            imagepng($this->createTargetImage(), $this->getTargetPath());
        } else if ($this->getTargetType() === 'image/gif') {
            imagegif($this->createTargetImage(), $this->getTargetPath());
        } else if($this->getTargetType() === 'image/webp') {
            imagewebp($this->createTargetImage(), $this->getTargetPath(), $this->getTargetQuality());
        }

        self::removeCachedImage($this->getTargetPath());

        return $this;
    }
}
