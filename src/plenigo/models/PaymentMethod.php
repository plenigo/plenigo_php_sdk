<?php

namespace plenigo\models;

/**
 * PaymentMethod
 *
 * <p>
 * An enum of the payment methods used to filter trabsactions
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class PaymentMethod {

    const BANK_ACCOUNT = "BANK_ACCOUNT";
    const CREDIT_CARD = "CREDIT_CARD";
    const PAYPAL = "PAYPAL";
    const BILLING = "BILLING";
    const ZERO = "ZERO";
    const SOFORT = "SOFORT";
    const POST_FINANCE = "POST_FINANCE";

}
