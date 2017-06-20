<?php

namespace plenigo\models;

/**
 * <p>
 * An enum of the transaction status used to filter trabsactions
 * </p>
 */
final class TransactionStatus {

    const BOOKED = "BOOKED";
    const DONE = "DONE";
    const CANCELED = "CANCELED";
    const CHARGEBACK = "CHARGEBACK";
    const FAILED = "FAILED";

}
