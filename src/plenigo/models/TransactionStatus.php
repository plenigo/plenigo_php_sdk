<?php

namespace plenigo\models;

/**
 * TransactionStatus
 *
 * <p>
 * An enum of the transaction status used to filter trabsactions
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class TransactionStatus {

    const BOOKED = "BOOKED";
    const DONE = "DONE";
    const CANCELED = "CANCELED";
    const CHARGEBACK = "CHARGEBACK";

}
