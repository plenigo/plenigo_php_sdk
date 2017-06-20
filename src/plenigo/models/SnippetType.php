<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/utils/BasicEnum.php';

use plenigo\internal\utils\BasicEnum;

/**
 * <p>
 * This class contains the parameters eeded to send the Snippet ID value to the SnippetConfig object
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 */
class SnippetType extends BasicEnum {

    /**
     * Personal profile snippet
     */
    const PERSONAL_DATA = "plenigo.Snippet.PERSONAL_DATA";
    
    /**
     * Order list snippet
     */
    const ORDER = "plenigo.Snippet.ORDER";
    
    /**
     * Subscription status snippet
     */
    const SUBSCRIPTION = "plenigo.Snippet.SUBSCRIPTION";
    
    /**
     * Payment methods screen snippet
     */
    const PAYMENT_METHODS = "plenigo.Snippet.PAYMENT_METHODS";
    
    /**
     * Address information snippet
     */
    const ADDRESS_DATA = "plenigo.Snippet.ADDRESS_DATA";

    /**
     * Billing address information snippet
     */
    const BILLING_ADDRESS_DATA_DATA = "plenigo.Snippet.BILLING_ADDRESS_DATA";

    /**
     * Delivery address information snippet
     */
    const DELIVERY_ADDRESS_DATA = "plenigo.Snippet.DELIVERY_ADDRESS_DATA";

    /**
     * Bank account snippet
     */
    const BANK_ACCOUNT = "plenigo.Snippet.BANK_ACCOUNT";

    /**
     * Credit card snippet
     */
    const CREDIT_CARD = "plenigo.Snippet.CREDIT_CARD";

    /**
     * Personal data settings snippet
     */
    const PERSONAL_DATA_SETTINGS = "plenigo.Snippet.PERSONAL_DATA_SETTINGS";

    /**
     * Personal data address snippet
     */
    const PERSONAL_DATA_ADDRESS = "plenigo.Snippet.PERSONAL_DATA_ADDRESS";

    /**
     * Personal data protection snippet
     */
    const PERSONAL_DATA_PROTECTION = "plenigo.Snippet.PERSONAL_DATA_PROTECTION";


    /**
     * Personal data social media snippet
     */
    const PERSONAL_DATA_SOCIAL_MEDIA = "plenigo.Snippet.PERSONAL_DATA_SOCIAL_MEDIA";

    /**
     * Personal data password snippet
     */
    const PERSONAL_DATA_PASSWORD = "plenigo.Snippet.PERSONAL_DATA_PASSWORD";
}
