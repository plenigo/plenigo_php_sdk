<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../models/CompanyUserList.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiURLs;
use \plenigo\internal\services\Service;
use plenigo\internal\utils\SdkUtils;
use \plenigo\models\CompanyUserList;
use \plenigo\models\ErrorCode;

/**
 * CompanyService
 *
 * <p>
 * A class used to retrieve Company specific data like company's users.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class CompanyService extends Service {

    const ERR_MSG_GET = "Error geting company users";

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
     * 
     * @param int $page
     * @param int $size
     * @return type
     */
    public static function getUserList($page = 0, $size = 10) {
        $map = array(
            'page' => SdkUtils::clampNumber($page, 0, null),
            'size' => SdkUtils::clampNumber($size, 10, 100)
        );

        $url = ApiURLs::COMPANY_USERS;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::COMPANY_USERS, self::ERR_MSG_GET);
        
        $result = CompanyUserList::createFromMap((array) $data);

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

}
