<?php

namespace plenigo\services;

require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/../internal/models/MeteredUserData.php';
require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';

use \plenigo\internal\services\Service;
use \plenigo\internal\models\MeteredUserData;
use \plenigo\PlenigoManager;
use \plenigo\services\UserService;
use \plenigo\PlenigoException;
use \plenigo\internal\utils\EncryptionUtils;

/**
 * MeterService
 * 
 * <b>
 * This contains the services related to metering user views with plenigo,
 * </b>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class MeterService extends Service
{

    /**
     * Metered data initialization vector.
     */
    const METERED_INIT_VECTOR = "7a134cc376d05cf6bc116e1e53c8801e";

    /**
     * Browser ID from the cookieID
     */
    const METERED_VIEW_BROWSER_ID = 0;

    /**
     * Metered view array index position.
     */
    const METERED_VIEW_ACTIVATED_IDX_POS = 1;

    /**
     * Free views allowed array index position.
     */
    const FREE_VIEWS_ALLOWED_IDX_POS = 2;

    /**
     * Free views taken array index position.
     */
    const FREE_VIEWS_TAKEN_IDX_POS = 3;

    /**
     * Limit reached array index position.
     */
    const FREE_VIEWS_LIMIT_REACHED_IDX_POS = 4;

    /**
     * Free views allowed (after login) array index position.
     */
    const LOGIN_FREE_VIEWS_ALLOWED_IDX_POS = 9;

    /**
     * Free views taken (after login) array index position.
     */
    const LOGIN_FREE_VIEWS_TAKEN_IDX_POS = 10;

    /**
     * Limit reached (after login) array index position.
     */
    const LOGIN_FREE_VIEWS_LIMIT_REACHED_IDX_POS = 11;

    /**
     * Time of the first page hit
     */
    const START_TIME_IDX_POS = 12;

    /**
     * Time period metered view counter is running. Possible values (DAY|WEEK|MONTH|YEAR)
     */
    const METERED_PERIOD_IDX_POS = 13;

    /**
     * Flag indicating if metered period starts with first visit or at first day / 0 o'clock, etc.
     */
    const START_WITH_FIRST_DAY_IDX_POS = 14;

    /**
     * Time as long indicating representing cookie creating time
     */
    const COOKIE_CREATION_TIME_IDX_POS = 15;

    /**
     * A day in milliseconds.
     * (24*60*60*100)
     */
    const TS_DAY_IN_MILLIS = 8640000; //24hours in millis

    /**
     * This method parses the metered view data from the user in the cookie.
     *
     * @return MeteredUserData The metered user data
     */

    private static function getMeteredUserData()
    {
        $cookieText = static::getCookieContents(PlenigoManager::PLENIGO_VIEW_COOKIE_NAME);
        if (is_null($cookieText) || trim($cookieText) == false) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo view cookie not set!!");
            return null;
        }
        $data = EncryptionUtils::decryptWithAES(
                PlenigoManager::get()->getCompanyId(), $cookieText, self::METERED_INIT_VECTOR);

        if (is_null($data) || strstr($data, '|') === false) {
            $clazz = get_class();
            PlenigoManager::error($clazz, "Cookie data could not be decrypted.");
            return null;
        }

        return static::parseMeteredUserData($data);
    }

    /**
     * Returns a flag indicating if the user still has free views left.
     * 
     * @return True if the user still has free views left, false otherwise
     */
    public static function hasFreeViews()
    {
        $clazz = get_class();
        $meteredUserData = static::getMeteredUserData();
        if (is_null($meteredUserData)) {
            $limitParam = filter_input(INPUT_GET, "meteredLimitReached");
            if ($limitParam !== FALSE && !is_null($limitParam) && $limitParam === 'true') {
                PlenigoManager::notice($clazz, "Limit reached by URL parameter. You shall NOT pass!");
                return false;
            }
            PlenigoManager::warn($clazz, "Returning TRUE but I have no metered Data!!!!!");
            return true;
        }
        $active = $meteredUserData->isMeteredViewActivated();
        if (is_null($active) || $active === false) {
            PlenigoManager::notice($clazz, "Metered view deactivated!!");
            return false;
        }
        $loggedIn = UserService::isLoggedIn();
        if (is_null($loggedIn)) {
            $loggedIn = false;
        }
        $limitReached = $meteredUserData->isLimitReached();
        if (is_null($limitReached)) {
            $limitReached = false;
        }
        $viewsAvailable = $meteredUserData->getFreeViewsAllowed();
        $viewsUsed = $meteredUserData->getFreeViewsTaken();
        $loginLimitReached = $meteredUserData->isLoginLimitReached();
        if (is_null($loginLimitReached)) {
            $loginLimitReached = false;
        }
        $loginViewsAvailable = $meteredUserData->getLoginFreeViewsAllowed();
        $loginViewsUsed = $meteredUserData->getLoginFreeViewsTaken();
        $validCookie = self::checkCookieValidity($meteredUserData);

        //invalid Metered cookie, the Javascript should take care of it
        if ($validCookie === false) {
            PlenigoManager::notice($clazz, "Invalid. You shall pass this time!");
            return true;
        }

        $limitToCheck = $limitReached;
        $viewsToCheck = $viewsAvailable;
        $viewsUsedToCheck = $viewsUsed;

        //if login views enabled
        if ($loggedIn === true && $loginViewsAvailable > 0) {
            $limitToCheck = $loginLimitReached;
            $viewsToCheck = $loginViewsAvailable;
            $viewsUsedToCheck = $loginViewsUsed;
        }

        if ($limitToCheck === true) {
            PlenigoManager::notice($clazz, "Limit reached. You shall NOT pass!");
            return false;
        }

        PlenigoManager::notice($clazz,
            "Limit not reached. You shall pass! (" . $viewsUsedToCheck . "/" . $viewsToCheck . ")");
        return true;
    }

    /**
     * Recovers the typified data of the cookie and returns a parsed object with variables
     * 
     * @param string $data the string representation of the data on the view cookie
     * @return MeteredUserData a parsed object with variables
     */
    private static function parseMeteredUserData($data)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Metered Data: " . print_r($data, true));
        $arr = explode('|', $data);
        $activated = safe_boolval($arr[self::METERED_VIEW_ACTIVATED_IDX_POS]);
        $allowed = intval($arr[self::FREE_VIEWS_ALLOWED_IDX_POS]);
        $taken = intval($arr[self::FREE_VIEWS_TAKEN_IDX_POS]);
        $reached = safe_boolval($arr[self::FREE_VIEWS_LIMIT_REACHED_IDX_POS]);
        $logAllowed = intval($arr[self::LOGIN_FREE_VIEWS_ALLOWED_IDX_POS]);
        $logTaken = intval($arr[self::LOGIN_FREE_VIEWS_TAKEN_IDX_POS]);
        $logReached = safe_boolval($arr[self::LOGIN_FREE_VIEWS_LIMIT_REACHED_IDX_POS]);
        $res = new MeteredUserData($activated, $allowed, $taken, $reached, $logAllowed, $logTaken, $logReached);
        $res->setStartTime(intval($arr[self::START_TIME_IDX_POS]));
        $res->setMeteredPeriod(trim($arr[self::START_TIME_IDX_POS]));
        $res->setStartWithFirstDay(safe_boolval($arr[self::START_WITH_FIRST_DAY_IDX_POS]));
        $res->setCookieCreationTime(intval($arr[self::COOKIE_CREATION_TIME_IDX_POS]));

        return $res;
    }

    /**
     * This method follows the logic of validating the creation time of the cookie, 
     * this is a coutnermeassure for cookie spoofing to get metered views always active
     * 
     * @param plenigo\internal\models\MeteredUserData $meteredInfo the Metered Info subject to checking
     * @return boolean true if is a valid cookie, false otherwise
     */
    private static function checkCookieValidity(MeteredUserData $meteredInfo)
    {
        $period = $meteredInfo->getMeteredPeriod();
        $curTime = time();
        $timeLapse = $curTime - $meteredInfo->getCookieCreationTime();

        $olderThanADay = false;
        $olderThanAWeek = false;
        $olderThanAMonth = false;
        $olderThanAYear = false;

        if ($timeLapse > 0 && intval($timeLapse) > intval(static::TS_DAY_IN_MILLIS)) {
            $olderThanADay = true;
        }
        if ($timeLapse > 0 && intval($timeLapse) > intval(static::TS_DAY_IN_MILLIS * 7)) {
            $olderThanAWeek = true;
        }
        if ($timeLapse > 0 && intval($timeLapse) > intval(static::TS_DAY_IN_MILLIS * 30)) {
            $olderThanAMonth = true;
        }
        if ($timeLapse > 0 && intval($timeLapse) > intval(static::TS_DAY_IN_MILLIS * 365)) {
            $olderThanAYear = true;
        }

        if ($period === 'DAY' && $olderThanADay === true) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "View Cookie older than a day.");
            return false;
        }
        if ($period === 'WEEK' && $olderThanAWeek === true) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "View Cookie older than a week.");
            return false;
        }
        if ($period === 'MONTH' && $olderThanAMonth === true) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "View Cookie older than a month.");
            return false;
        }
        if ($period === 'YEAR' && $olderThanAYear === true) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "View Cookie older than a year.");
            return false;
        }

        return true;
    }

}
