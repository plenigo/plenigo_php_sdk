<?php

namespace plenigo\models;

/**
 * Image
 * 
 * <b>
 * This object contains image information related to a product
 * </b>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class Image {

    private $url = null;
    private $description = null;
    private $altText = null;

    /**
     * Image constructor.
     * @param string $url The URL of the image
     * @param string $description The description of the image
     * @param string $altText The alt text of the image
     */
    public function __construct($url, $description, $altText) {
        $this->url = $url;
        $this->description = $description;
        $this->altText = $altText;
    }

    /**
     * The URL of the image.
     * @return The URL of the image
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * The Description of the image.
     * @return The Description of the image
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * The alt text of the image.
     * @return The alt text of the image
     */
    public function getAltText() {
        return $this->altText;
    }

    /**
     * Creates a Image instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return Image instance.
     */
    public static function createFromMap($map) {
        $url = isset($map['url']) ? $map['url'] : null;
        $description = isset($map['description']) ? $map['description'] : null;
        $altText = isset($map['altText']) ? $map['altText'] : null;

        return new Image($url, $description, $altText);
    }

    /**
     * Creates a Image array instance from an array map.
     *
     * @param array $map The array map to use for lopping the instance creation.
     * @return array of Image instances.
     */
    public static function createFromMapArray($map) {
        $imgArray = isset($map['images']) ? $map['images'] : null;
        $resArray = array();
        if (!is_null($imgArray) && is_array($imgArray)) {
            foreach ($imgArray as $img) {
                $resObj = static::createFromMap(get_object_vars($img));
                if (!is_null($resObj)) {
                    array_push($resArray, $resObj);
                }
            }
        }
        return $resArray;
    }

}
