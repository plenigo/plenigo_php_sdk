<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../models/CompanyUserList.php';
require_once __DIR__ . '/../models/FailedPaymentList.php';
require_once __DIR__ . '/../models/OrderList.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use plenigo\internal\ApiURLs;
use plenigo\internal\ApiParams;
use plenigo\internal\exceptions\RegistrationException;
use plenigo\internal\services\Service;
use plenigo\internal\utils\SdkUtils;
use plenigo\models\CompanyUserList;
use plenigo\models\FailedPaymentList;
use plenigo\models\Order;
use plenigo\models\OrderList;
use plenigo\models\SubscriptionList;
use plenigo\PlenigoException;
use plenigo\internal\utils\RestClient;

/**
 * <p>
 * A class used to retrieve Company specific data like company's users.
 * </p>
 */
class CompanyService extends Service {

    const ERR_MSG_GET = "Error getting company users";
    const ERR_MSG_GET_FAILED = "Error getting failed payments";
    const ERR_MSG_GET_ORDERS = "Error getting orders";
    const ERR_MSG_GET_ORDER = "Error getting order";

    /**
     * The constructor for the CompanyService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return CompanyService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Returns a list of users of the specified company.
     * 
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     * 
     * @return CompanyUserList A list of users of the specified company
     */
    public static function getUserList($page = 0, $size = 10) {
        $map = array(
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000)
        );

        $url = ApiURLs::COMPANY_USERS;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::COMPANY_USERS, self::ERR_MSG_GET);

        $result = CompanyUserList::createFromMap((array) $data);

        return $result;
    }

    /**
     * Returns a list of users of the specified company.
     *
     * @param string $startDate date od the startdate of selection (format YYYY-MM-DD)
     * @param string $endDate date od the enddate of selection - must be greater then $startDate (format YYYY-MM-DD)
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return array  A list of users (Objects) of the specified company
     */
    public static function getChangedUsers($startDate = '-1 week', $endDate = 'now', $page = 0, $size = 10) {

        $map = array(
            'startDate' => date("Y-m-d H:i", strtotime($startDate)),
            'endDate' => date("Y-m-d H:i",strtotime($endDate)),
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000)
        );

        if ($size === 0) {
            $map['size'] = 0;
        }

        $url = ApiURLs::COMPANY_USERS_CHANGED;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::COMPANY_USERS_CHANGED, self::ERR_MSG_GET);

        $result = (array) $data;

        return $result;
    }

    /**
     * Returns a list of payments of the specified company.
     *
     * @param string $startDate date od the startdate of selection (format YYYY-MM-DD)
     * @param string $endDate date od the enddate of selection - must be greater then $startDate (format YYYY-MM-DD)
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return array  A list of payments of the specified company
     */
    public static function getIncomingPayments($startDate = '-1 week', $endDate = 'now', $page = 0, $size = 10) {

        $testMode = false;
        $map = array(
            'startDate' => date("Y-m-d", strtotime($startDate)),
            'endDate' => date("Y-m-d",strtotime($endDate)),
            'testMode' => $testMode ? 'true' : 'false',
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000)
        );

        $url = ApiURLs::COMPANY_INCOMING_PAYMENTS;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::COMPANY_INCOMING_PAYMENTS, self::ERR_MSG_GET);

        $result = (array) $data;

        return $result;
    }

    /**
     * Returns a list of invoices of the specified company.
     *
     * @param string $startDate date od the startdate of selection (format YYYY-MM-DD)
     * @param string $endDate date od the enddate of selection - must be greater then $startDate (format YYYY-MM-DD)
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return array  A list of invoices of the specified company
     */
    public static function getInvoices($startDate = '-1 week', $endDate = 'now', $page = 0, $size = 10) {

        $testMode = false;
        $map = array(
            'startDate' => date("Y-m-d", strtotime($startDate)),
            'endDate' => date("Y-m-d",strtotime($endDate)),
            'testMode' => $testMode ? 'true' : 'false',
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000)
        );

        $url = ApiURLs::COMPANY_INVOICES;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::COMPANY_INVOICES, self::ERR_MSG_GET);

        $result = (array) $data;

        return $result;
    }

    /**
     * Returns a list of users based on the given ids.
     * 
     * @param string $userIds a comma separated list if ids
     * @param boolean $useExternalCustomerId (optional) Flag indicating if customer id sent is the external customer id
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return CompanyUserList A  list of users of the specified company with the given ids
     */
    public static function getUserByIds($userIds = "", $useExternalCustomerId = false) {

        $params = array(
            'userIds' => $userIds,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );

        $url = ApiURLs::COMPANY_USERS_SELECT;

        $request = static::getRequest($url, false, $params);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, $url, self::ERR_MSG_GET);

        $result = CompanyUserList::createFromArray((array) $data);

        return $result;
    }

    /**
     * Returns a list of failed payments based on date, status and paging filters.
     * 
     * NOTE: Date interval must be in the past and can not be more than 6 months long.
     * 
     * @param string $start Date start of the interval (String format YYYY-MM-DD)
     * @param string $end Date end of the interval (String format YYYY-MM-DD)
     * @param string $status Status of the failed payment (FAILED, FIXED, FIXED_MANUALLY)
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     * 
     * @return FailedPaymentList A paginated list of FailedPayment objects
     */
    public static function getFailedPayments($start = null, $end = null, $status = null, $page = 0, $size = 10) {
        // sanitize dates
        $end = (!is_null($end)) ? $end : date("Y-m-d"); // if no end date the send today
        // check end date is not in the future
        $dFuture = new \DateTime($end);
        $dNow = new \DateTime();
        if ($dFuture > $dNow) {
            $end = date("Y-m-d");
        }

        // Check that start date is valid
        if (is_null($start)) {
            $dEnd = new \DateTime($end);
            $start = date("Y-m-d", strtotime("-6 MONTH", $dEnd)); // 6 month before end date
        }

        // month diff (max 6 months)
        $d1 = new \DateTime($start);
        $d2 = new \DateTime($end);
        $months = ($d1->diff($d2)->m + ($d1->diff($d2)->y * 12));
        if ($months > 6) {
            $start = date("Y-m-d", strtotime("-6 MONTH", $d2));
        }

        // parameter array
        $map = array(
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000),
            'startDate' => $start,
            'endDate' => $end
        );
        
        // add status if needed
        if(!is_null($status)){
            $map['failedPaymentStatus'] = $status;
        }

        $url = ApiURLs::COMPANY_FAILED_PAYMENTS;

        $request = static::getRequest($url, false, $map);
        $fpRequest = new static($request);
        $data = parent::executeRequest($fpRequest, ApiURLs::COMPANY_FAILED_PAYMENTS, self::ERR_MSG_GET_FAILED);
        $result = FailedPaymentList::createFromMap((array) $data);

        return $result;
    }


    /**
     * Returns an order from API
     *
     * @param string $id ID of the order
     * @param bool $testMode
     * @return Order the requested order
     * @throws PlenigoException | RegistrationException
     */
    public static function getOrder($id, $testMode = false) {

        if (empty($id)) {
            throw new PlenigoException("Order ID should not be empty!");
        }

        $url = str_ireplace(ApiParams::URL_ORDER_ID_TAG, $id, ApiURLs::COMPANY_ORDER);
        $map = array(
            'testMode' => $testMode ? 'true' : 'false'
        );

        $request = static::getRequest($url, false, $map);
        $fpRequest = new static($request);
        $data = parent::executeRequest($fpRequest, ApiURLs::COMPANY_ORDER, self::ERR_MSG_GET_ORDER);
        $result = Order::createFromMap((array) $data);

        return $result;
    }

    /**
     * Returns a list of orders of the specified company.
     *
     * @param string $start Date start of the interval (String format YYYY-MM-DD)
     * @param string $end Date end of the interval (String format YYYY-MM-DD)
     * @param bool $testMode Test mode Flag
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return OrderList
     */
    public static function getOrders($start = null, $end = null, $testMode = false , $page = 0, $size = 10) {
        // sanitize dates
        $end = (!is_null($end)) ? $end : date("Y-m-d"); // if no end date the send today
        // check end date is not in the future
        $dFuture = new \DateTime($end);
        $dNow = new \DateTime();
        if ($dFuture > $dNow) {
            $end = date("Y-m-d");
        }

        // Check that start date is valid
        if (is_null($start)) {
            $dEnd = new \DateTime($end);
            $start = date("Y-m-d", strtotime("-12 MONTH", $dEnd->getTimestamp() )); // 6 month before end date
        }

        // parameter array
        $map = array(
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000),
            'startDate' => $start,
            'endDate' => $end,
            'testMode' => $testMode ? 'true' : 'false'
        );

        $url = ApiURLs::COMPANY_ORDERS;

        $request = static::getRequest($url, false, $map);
        $fpRequest = new static($request);
        $data = parent::executeRequest($fpRequest, ApiURLs::COMPANY_ORDERS, self::ERR_MSG_GET_ORDERS);
        $result = OrderList::createFromMap((array) $data);

        return $result;
    }

    /**
     * Returns a list of subscriptions of the specified company.
     *
     * @param string $start Date start of the interval (String format YYYY-MM-DD)
     * @param string $end Date end of the interval (String format YYYY-MM-DD)
     * @param bool $testMode Test mode Flag
     * @param int $page Number of the page (starting from 0)
     * @param int $size Size of the page - must be between 10 and 100
     *
     * @throws PlenigoException | RegistrationException
     *
     * @return mixed list of subscriptions
     */
    public static function getSubscriptions($start = null, $end = null, $testMode = false , $page = 0, $size = 10) {
        // check end date is not in the future
        $dFuture = new \DateTime($end);
        $dNow = new \DateTime();
        if ($dFuture > $dNow) {
            $end = date("Y-m-d");
        }
        // parameter array
        $map = array(
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 1000),
            'startDate' => $start,
            'endDate' => $end,
            'testMode' => $testMode ? 'true' : 'false'
        );

        $url = ApiURLs::COMPANY_SUBSCRIPTIONS;

        $request = static::getRequest($url, false, $map);
        $fpRequest = new static($request);
        $data = parent::executeRequest($fpRequest, ApiURLs::COMPANY_SUBSCRIPTIONS, self::ERR_MSG_GET_ORDERS);
        $result = SubscriptionList::createFromMap((array) $data);

        return $result;
    }
    
    /**
     * Executes the prepared request and returns the Response object on success.
     *
     * @return string The request's response.
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

}
