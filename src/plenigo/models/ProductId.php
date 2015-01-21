<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/models/Product.php';
require_once __DIR__ . '/../internal/utils/ArrayUtils.php';

use \plenigo\internal\models\Product;
use \plenigo\internal\utils\ArrayUtils;

/**
 * ProductId
 *
 * <p>
 * This class represents a product in the plenigo platform.
 * A product can be any digital content.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @author   Ricardo Torres <r.torres@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ProductId implements Product
{
    /**
     * The product id.
     */
    private $id;

    /**
     * This constructor receives the product id as parameters.
     *
     * @param string $productId The product id.
     */
    public function __construct($productId)
    {
        $this->id = $productId;
    }

    /**
     * Gets the ID.
     *
     * @return string Returns the id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns an array map from the product's values.
     *
     * @return array The map data for the product.
     */
    public function getMap()
    {
        $map = array();

        ArrayUtils::addIfNotNull($map, 'id', $this->getId());

        return $map;
    }
}