<?php

namespace plenigo\models;

/**
 * Product
 *
 * <p>
 * This object represents the login configuration for the
 * plenigo Javascript API's login method.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @author   Ricardo Torres <r.torres@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class LoginConfig
{

    /**
     * The URI to redirect to when the login is done.
     */
    private $redirectUri;

    /**
     * The data access accessScope.
     */
    private $accessScope;

    /**
     * The constructor with the required parameters, this object is meant to be
     * used with at least these parameters.
     *
     * @param string $redUri          The redirect URL
     * @param const  $dataAccessScope The data access scope
     */
    public function __construct($redUri, $dataAccessScope)
    {
        $this->redirectUri = $redUri;
        $this->accessScope = $dataAccessScope;
    }

    /**
     * The URL to be redirected to after successful login to finish up the server side workflow.
     * The given URL (or at least the starting part) must be registered in the plenigo backend.
     * Otherwise an error is returned.
     *
     * @return The redirect URL
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * The data access accessScope.
     *
     * @return The data access scope
     */
    public function getAccessScope()
    {
        return $this->accessScope;
    }

}