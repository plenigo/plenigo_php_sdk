<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/models/PricingData.php';

use \plenigo\internal\models\PricingData;

/**
 * CategoryData
 * 
 * <b>
 * This class contains category information from a plenigo defined category. A category can be any
 * digital content.
 * </b>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Maximilian Schweitzer <maximilian.schweitzer@plenigo.com>
 * @link     https://www.plenigo.com
 */
class CategoryData {

    private $id = null;
    private $pricingData = null;
    private $validityTime = null;
    private $title = null;

    /**
     * Category data constructor, must be filled with the required data.
     *
     * @param string       $id           The category id
     * @param PricingData  $pricingData  The pricing information
     * @param string       $validityTime The time span in days the category is valid
     * @param string       $title        The title of category
     */
    public function __construct($id, $pricingData, $validityTime, $title) {
        $this->id = $id;
        $this->validityTime = $validityTime;
        $this->title = $title;
        if (!is_null($pricingData)) {
            $this->pricingData = $pricingData;
        } else {
            $this->pricingData = new PricingData(false, 0.0, 0.0, "");
        }
    }

    /**
     * Id of the category.
     *
     * @return The id of the category
     */
    public function getId() {
        return $this->id;
    }

    /**
     * The price of the category.
     *
     * @return the price of the category
     */
    public function getPrice() {
        return $this->pricingData->getAmount();
    }

    /**
     * The product type.
     *
     * @return string product type
     */
    public function getType() {
        return $this->pricingData->getType();
    }

    /**
     * The title of Category
     *
     * @return string Category-Title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Currency as ISO 4217 code, e.g. EUR .
     *
     * @return the currency iso code
     */
    public function getCurrency() {
        return $this->pricingData->getCurrency();
    }

    /**
     * The time span in days the category is valid.
     *
     * @return time span in days the category is valid
     */
    public function getValidityTime() {
        return $this->validityTime;
    }

    /**
     * Creates a ProductData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return ProductData instance.
     */
    public static function createFromMap($map) {
        $title = isset($map['title']) ? $map['title'] : null;
        $currID = isset($map['id']) ? $map['id'] : null;
        $pricingData = PricingData::createFromMap($map);
        $currValid = isset($map['validityTime']) ? $map['validityTime'] : null;

        return new CategoryData($currID, $pricingData, $currValid, $title);
    }

}
