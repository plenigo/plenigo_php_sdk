<?php

namespace plenigo\internal\models;

/**
 * MeteredUserData
 * 
 * <p>
 * This object represents the metered user data.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'default' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class MeteredUserData
{

    private $isMeteredViewActivated = false;
    private $freeViewsAllowed = 0;
    private $freeViewsTaken = 0;
    private $isLimitReached = false;
    private $loginFreeViewsAllowed = 0;
    private $loginFreeViewsTaken = 0;
    private $loginIsLimitReached = false;
    private $startTime = 0;
    private $meteredPeriod = 'DAY';
    private $startWithFirstDay = true;
    private $cookieCreationTime = 0;

    /**
     * Required constructor.
     *
     * @param bool $activated indicates if the metered view is activated
     * @param int $allowed           indicates how many free views are allowed
     * @param int $taken             indicates the amount of views that the user has taken
     * @param bool $reached         indicates if the limit has been reached
     * @param int $logAllowed      indicates how many free views are allowed (after login)
     * @param int $logTaken        indicates the amount of views that the user has taken (after login)
     * @param bool $logReached    indicates if the limit has been reached (after login)
     */
    public function __construct($activated, $allowed, $taken, $reached, $logAllowed, $logTaken, $logReached)
    {
        $this->isMeteredViewActivated = $activated;
        $this->freeViewsAllowed = $allowed;
        $this->freeViewsTaken = $taken;
        $this->isLimitReached = $reached;
        $this->loginFreeViewsAllowed = $logAllowed;
        $this->loginFreeViewsTaken = $logTaken;
        $this->loginIsLimitReached = $logReached;
    }

    /**
     * Return the isMeteredViewActivated value
     * 
     * @return bool
     */
    public function isMeteredViewActivated()
    {
        return $this->isMeteredViewActivated;
    }

    /**
     * Return the freeViewsAllowed value
     * 
     * @return int
     */
    public function getFreeViewsAllowed()
    {
        return $this->freeViewsAllowed;
    }

    /**
     * Return the viewsTaken value
     * 
     * @return int
     */
    public function getFreeViewsTaken()
    {
        return $this->freeViewsTaken;
    }

    /**
     * Return the isLimitReached value
     * 
     * @return bool
     */
    public function isLimitReached()
    {
        return $this->isLimitReached;
    }

    /**
     * Return the loginFreeViewsAllowed value
     * 
     * @return int
     */
    public function getLoginFreeViewsAllowed()
    {
        return $this->loginFreeViewsAllowed;
    }

    /**
     * Return the loginFreeViewsTaken value
     * 
     * @return int
     */
    public function getLoginFreeViewsTaken()
    {
        return $this->loginFreeViewsTaken;
    }

    /**
     * Return the loginIsLimitReached value
     * 
     * @return bool
     */
    public function isLoginLimitReached()
    {
        return $this->loginIsLimitReached;
    }

    /**
     * Return the startTime value
     * 
     * @return int
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Return the meteredPeriod value
     * 
     * @return string
     */
    public function getMeteredPeriod()
    {
        return $this->meteredPeriod;
    }

    /**
     * Return the startWithFirstDay value
     * 
     * @return bool
     */
    public function getStartWithFirstDay()
    {
        return $this->startWithFirstDay;
    }

    /**
     * Return the cookieCreationTime value
     * 
     * @return int
     */
    public function getCookieCreationTime()
    {
        return $this->cookieCreationTime;
    }

    /**
     * Sets the isMeteredViewActivated value
     * 
     * @param bool $isMeteredViewActivated The value to set
     */
    public function setMeteredViewActivated($isMeteredViewActivated)
    {
        $this->isMeteredViewActivated = $isMeteredViewActivated;
    }

    /**
     * Sets the freeViewsAllowed value
     * 
     * @param int $freeViewsAllowed The value to set
     */
    public function setFreeViewsAllowed($freeViewsAllowed)
    {
        $this->freeViewsAllowed = $freeViewsAllowed;
    }

    /**
     * Sets the viewsTaken value
     * 
     * @param int $freeViewsTaken The value to set
     */
    public function setFreeViewsTaken($freeViewsTaken)
    {
        $this->freeViewsTaken = $freeViewsTaken;
    }

    /**
     * Sets the isLimitReached value
     * 
     * @param bool $isLimitReached The value to set
     */
    public function setLimitReached($isLimitReached)
    {
        $this->isLimitReached = $isLimitReached;
    }

    /**
     * Sets the loginFreeViewsAllowed value
     * 
     * @param int $loginFreeViewsAllowed The value to set
     */
    public function setLoginFreeViewsAllowed($loginFreeViewsAllowed)
    {
        $this->loginFreeViewsAllowed = $loginFreeViewsAllowed;
    }

    /**
     * Sets the loginFreeViewsTaken value
     * 
     * @param int $loginFreeViewsTaken The value to set
     */
    public function setLoginFreeViewsTaken($loginFreeViewsTaken)
    {
        $this->loginFreeViewsTaken = $loginFreeViewsTaken;
    }

    /**
     * Sets the loginIsLimitReached value
     * 
     * @param int $loginIsLimitReached The value to set
     */
    public function setLoginIsLimitReached($loginIsLimitReached)
    {
        $this->loginIsLimitReached = $loginIsLimitReached;
    }

    /**
     * Sets the startTime value
     * 
     * @param int $startTime The value to set
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * Sets the meteredPeriod value
     * 
     * @param string $meteredPeriod The value to set
     */
    public function setMeteredPeriod($meteredPeriod)
    {
        $this->meteredPeriod = $meteredPeriod;
    }

    /**
     * Sets the startWithFirstDay value
     * 
     * @param bool $startWithFirstDay The value to set
     */
    public function setStartWithFirstDay($startWithFirstDay)
    {
        $this->startWithFirstDay = $startWithFirstDay;
    }

    /**
     * Sets the cookieCreationTime value
     * 
     * @param int $cookieCreationTime The value to set
     */
    public function setCookieCreationTime($cookieCreationTime)
    {
        $this->cookieCreationTime = $cookieCreationTime;
    }

}
