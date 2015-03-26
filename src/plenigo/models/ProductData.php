<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/models/PricingData.php';
require_once __DIR__ . '/../internal/models/Subscription.php';
require_once __DIR__ . '/../internal/models/ActionPeriod.php';
require_once __DIR__ . '/Image.php';

use \plenigo\internal\models\PricingData;
use \plenigo\internal\models\Subscription;
use \plenigo\internal\models\ActionPeriod;
use \plenigo\models\Image;

/**
 * ProductData
 * 
 * <b>
 * This class contains product information from a plenigo defined product. A product can be any
 * digital content.
 * </b>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ProductData {

    private $id = null;
    private $subscription = null;
    private $title = null;
    private $description = null;
    private $collectible = false;
    private $pricingData = null;
    private $actionPeriod = null;
    private $images = array();
    private $videoPrequelTime = null;

    /**
     * Product Data constructor, must be filled with the required data.
     *
     * @param string       $id           The product id
     * @param Subscription $subscript    The product subscription
     * @param string       $title        The title
     * @param string       $desc         The description
     * @param bool      $collect      Flag indicating if product is part of the collectible model
     * @param PricingData  $pricingData  The pricing information
     * @param ActionPeriod $actionPeriod The action period information
     * @param array()      $images       The images information related to the product
     */
    public function __construct($id, $subscript, $title, $desc, $collect, $pricingData, $actionPeriod, $images) {
        $this->id = $id;
        if (!is_null($subscript)) {
            $this->subscription = $subscript;
        } else {
            $this->subscription = new Subscription(false, 0, 0, false);
        }
        $this->title = $title;
        $this->description = $desc;
        $this->collectible = ($collect === true);
        if (!is_null($pricingData)) {
            $this->pricingData = $pricingData;
        } else {
            $this->pricingData = new PricingData(false, 0.0, 0.0, "");
        }
        if (!is_null($actionPeriod)) {
            $this->actionPeriod = $actionPeriod;
        } else {
            $this->actionPeriod = new ActionPeriod("", 0, 0.0);
        }
        if (is_array($images)) {
            $this->images = $images;
        }
    }

    /**
     * Id of the product.
     *
     * @return The id of the product
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Title of the product.
     *
     * @return The title of the product
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Description of the product.
     *
     * @return The description of the product
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Flag indicating if product is part of the collectible model.
     *
     * @return  a bool indicating if the product is collectible
     */
    public function isCollectible() {
        return ($this->collectible === true);
    }

    /**
     * An array of images that refer to information images of the product.
     *
     * @return The images array
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * Flag indicating if product represents a subscription.
     *
     * @return a bool indicating if the product represents a subscription
     */
    public function isSubscribable() {
        return $this->subscription->isSubscribable();
    }

    /**
     * Flag indicating if the product price can be freely selected by the user.
     *
     * @return A bool indicating if the user can select the price or not
     */
    public function isPriceChosen() {
        return $this->pricingData->isChoosePrice();
    }

    /**
     * The price of the product.
     *
     * @return the price of the product
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
     * Currency as ISO 4217 code, e.g. EUR .
     *
     * @return the currency iso code
     */
    public function getCurrency() {
        return $this->pricingData->getCurrency();
    }

    /**
     * Subscription term.
     *
     * @return the subscription term
     */
    public function getSubscriptionTerm() {
        return $this->subscription->getTerm();
    }

    /**
     * Cancellation period for the subscription.
     *
     * @return the cancellation period for the subscription
     */
    public function getCancellationPeriod() {
        return $this->subscription->getCancellationPeriod();
    }

    /**
     * Flag indicating if the subscription is auto renewed.
     *
     * @return a bool indicating if the subscription is auto-renewed
     */
    public function isAutoRenewed() {
        return $this->subscription->isAutoRenewed();
    }

    /**
     * Name of the action period if one is defined.
     *
     * @return The name of the action period
     */
    public function getActionPeriodName() {
        return $this->actionPeriod->getName();
    }

    /**
     * Term of the action period if one is defined.
     *
     * @return The term of the action period
     */
    public function getActionPeriodTerm() {
        return $this->actionPeriod->getTerm();
    }

    /**
     * Price of the action period if one is defined.
     *
     * @return The action period if one is defined
     */
    public function getActionPeriodPrice() {
        return $this->actionPeriod->getPrice();
    }

    /**
     * Duration of the Video Prequel if defined
     *
     * @return Duration of the Video Prequel if defined
     */
    public function getVideoPrequelTime() {
        return $this->videoPrequelTime;
    }

    /**
     * Sets the Duration of the Video Prequel
     * 
     * @param int $videoPrequelTime
     */
    public function setVideoPrequelTime($videoPrequelTime) {
        $this->videoPrequelTime = $videoPrequelTime;
    }

    /**
     * Creates a ProductData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return ProductData instance.
     */
    public static function createFromMap($map) {
        $images = Image::createFromMapArray($map);
        $actionPeriod = ActionPeriod::createFromMap($map);
        $subscription = Subscription::createFromMap($map);
        $pricingData = PricingData::createFromMap($map);
        $currID = isset($map['id']) ? $map['id'] : null;
        $currTitle = isset($map['title']) ? $map['title'] : null;
        $currDesc = isset($map['description']) ? $map['description'] : null;
        $currCollectible = isset($map['collectible']) ? $map['collectible'] : null;

        $data = new ProductData(
                $currID, $subscription, $currTitle, $currDesc, $currCollectible, $pricingData, $actionPeriod, $images
        );

        if (isset($map['videoPrequelTime']) && !is_null($map['videoPrequelTime'])) {
            $data->setVideoPrequelTime($map['videoPrequelTime']);
        }

        return $data;
    }

}
