<?php

namespace plenigo\models;

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
 *
 * @category SDK
 * @package  PlenigoModels
 * @author Sebastian Dieguez <sebastian.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
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

}
