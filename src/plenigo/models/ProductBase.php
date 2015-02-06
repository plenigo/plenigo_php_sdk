<?php

namespace plenigo\models;

require_once __DIR__ . '/ProductId.php';
require_once __DIR__ . '/../internal/utils/ArrayUtils.php';

use \plenigo\models\ProductId;
use \plenigo\internal\utils\ArrayUtils;

/**
 * Product
 *
 * <p>
 * This class represents a product created on the fly by the user.
 * A product can be any digital content.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @author   Ricardo Torres <r.torres@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ProductBase extends ProductId
{
    const TYPE_EBOOK = "EBOOK";
    const TYPE_DIGITALNEWSPAPER = "DIGITALNEWSPAPER";
    const TYPE_DOWNLOAD = "DOWNLOAD";
    const TYPE_VIDEO = "VIDEO";
    const TYPE_MUSIC = "MUSIC";

    /**
     * The price of the product.
     */
    private $price;

    /**
     * The product title.
     */
    private $title;

    /**
     * The currency of the price.
     */
    private $currency;

    /**
     * Product type.
     */
    private $type;

    /**
     * Category ID for this Product
     * 
     * @var string 
     */
    private $categoryId;
    
    /**
     * Flag indicating if it is a pay what you want payment process.
     */
    private $customAmount;

    /**
     * Flag indicating if this checkout is a subscription renewal
     * @var bool
     */
    private $subscriptionRenewal;
    
    /**
     * This constructor receives price, title, id and currency as parameters, it
     * is recommended for instantiating products that are not managed by
     * plenigo.
     *
     * @param string|int $id        The product identifier.
     * @param string     $prodTitle The product title.
     * @param float      $prodPrice The product price.
     * @param string     $curr      The currency.
     */
    public function __construct($id, $prodTitle=null, $prodPrice=null, $curr=null)
    {
        parent::__construct($id);

        $this->price = $prodPrice;
        $this->title = $prodTitle;
        $this->currency = $curr;
    }

    /**
     * Returns the product type.
     *
     * @return string product type.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the product type.
     *
     * @param string $type product type
     *
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the category ID.
     *
     * @return string Category ID
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Sets the Category ID.
     *
     * @param string $ci The Category ID to set.
     */
    public function setCategoryId($ci)
    {
        $this->categoryId = $ci;
    }

    /**
     * Gets the currency.
     *
     * @return float Returns the currency.
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Gets the price.
     *
     * @return float Returns the price.
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Gets the title.
     *
     * @return string Returns the title.
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Checks if the custom ammount flag is set.
     *
     * @return bool The customAmount.
     */
    public function getCustomAmount()
    {
        return $this->customAmount === true;
    }

    /**
     * Sets the custom ammount flag.
     *
     * @param bool $condition The condition to set.
     *
     * @return void
     */
    public function setCustomAmount($condition)
    {
        $this->customAmount = safe_boolval($condition);
    }

    public function getSubscriptionRenewal()
    {
        return $this->subscriptionRenewal;
    }

    public function setSubscriptionRenewal($subscriptionRenewal)
    {
        $this->subscriptionRenewal = $subscriptionRenewal;
    }

    /**
     * Returns an array map from the product's values.
     *
     * @return array The map data for the product.
     */
    public function getMap()
    {
        $map = parent::getMap();

        ArrayUtils::addIfNotNull($map, 'price', $this->getPrice());
        ArrayUtils::addIfNotNull($map, 'title', $this->getTitle());
        ArrayUtils::addIfNotNull($map, 'currency', $this->getCurrency());
        ArrayUtils::addIfNotNull($map, 'categoryId', $this->getCategoryId());
        ArrayUtils::addIfNotNull($map, 'type', $this->getType());
        ArrayUtils::addIfNotNull($map, 'customAmount', $this->getCustomAmount());
        ArrayUtils::addIfNotNull($map, 'subscriptionRenewal', $this->getSubscriptionRenewal());

        return $map;
    }
}