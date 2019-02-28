<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../models/TransactionList.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\internal\utils\SdkUtils;
use plenigo\models\TransactionList;
use plenigo\PlenigoException;

/**
 * <p>
 * A class used to retrieve Transaction data for a company
 * </p>
 */
class TransactionService extends Service {

    const ERR_MSG_GET = "Error getting transaction data";
    const MONTHS_RANGE = 18;

    /**
     * The constructor for the TransactionService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return TransactionService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Returns a list of transactions of the specified company
     * 
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     * @param mixed $startDate Start date range of the transaction, must be timestamp (Date range must not exceed 6 months).
     * @param mixed $endDate End date range of the transaction, must be timestamp (Date range must not exceed 6 months).
     * @param string $payMethod Payment method used to pay the transaction. See PaymentMethod class
     * @param string $txStatus Status of the transaction. See TransactionStatus class
     * @return TransactionList A list of transactions of the specified company
     */
    public static function searchTransactions($page = 0, $size = 10, $startDate = null, $endDate = null, $payMethod = null, $txStatus = null) {

        $arrayDates = TransactionService::sanitizeDates($startDate, $endDate);

        $map = array(
            'startDate' => date("Y-m-d", $arrayDates[0]),
            'endDate' => date("Y-m-d", $arrayDates[1]),
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 100)
        );
        if (!is_null($payMethod) && is_string($payMethod)) {
            $map['paymentMethod'] = $payMethod;
        }
        if (!is_null($txStatus) && !is_string($txStatus)) {
            $map['transactionStatus'] = $txStatus;
        }

        $url = ApiURLs::TX_SEARCH;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::TX_SEARCH, self::ERR_MSG_GET);

        $result = TransactionList::createFromMap((array) $data);

        return $result;
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return The request's response.
     *
     * @throws PlenigoException on request error.
     */
    public function execute() {
        try {
            $response = parent::execute();
        } catch (\Exception $exc) {
            throw new PlenigoException('Company Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

    private static function sanitizeDates($startDate = null, $endDate = null) {
        $res = array();

        $morning = strtotime('today');

        // Null checks
        if (is_null($endDate) || !is_numeric($endDate)) {
            $endDate = $morning;
        }

        // 6 month range check
        // If the date is after this morning or is in the future, we clamp it
        if ($endDate > $morning) {
            $endDate = $morning;
        }
        //Check the range from the sanitized endDate and NULL check for the start date
        $minusSixMonths = strtotime("-" . self::MONTHS_RANGE . " months", $endDate);

        if ($startDate < $minusSixMonths || is_null($startDate) || !is_numeric($startDate) || $startDate > $endDate) {
            $startDate = $minusSixMonths;
        }

        //Now with the sanitized
        array_push($res, $startDate);
        array_push($res, $endDate);

        return $res;
    }

}
