<?php

namespace plenigo\models;

/**
 * <p>
 * An enum of the payment methods used to filter trabsactions
 * </p>
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
