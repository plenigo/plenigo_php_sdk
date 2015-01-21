<?php

namespace plenigo\builders;

require_once __DIR__ . '/../models/AccessScope.php';
require_once __DIR__ . '/../models/LoginConfig.php';

use \plenigo\models\AccessScope;
use \plenigo\models\LoginConfig;

/**
 * CheckoutSnippetBuilder
 *
 * <p>
 * This class builds a plenigo's Javascript API login that is
 * compliant.
 * </p>
 *
 * @category SDK
 * @package  PlenigoBuilders
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class LoginSnippetBuilder
{

    /**
     * The data required to build the snippet.
     */
    private $loginConfig;

    /**
     * The cross-site request forgery (CSRF) token.
     */
    private $csrfToken;

    /**
     * <p>
     * Constructor of the snippet builder, this requires a {@link \plenigo\models\LoginConfig} object.
     * </p>
     *
     * <p>
     * If no LoginConfig is passed, the SDK will try to login the user without using single sign on.
     * </p>
     *
     * @param \plenigo\models\LoginConfig $data The login data used.
     */
    public function __construct(LoginConfig $data = null)
    {
        if (is_null($data)) {
            $this->loginConfig = new \plenigo\models\LoginConfig('', null);
        } else {
            $this->loginConfig = $data;
        }
    }

    /**
     * When this method is called before the {@link \plenigo\builders\LoginSnippetBuilder#build()}
     * method, when the login snippet is built, it will fill out the state parameter of the Javascript SDK
     * login function with a cross-site request forgery (CSRF) token.
     *
     * @param string $token The provided CSRF token
     *
     * @return The same {@link \plenigo\builders\LoginSnippetBuilder} instance
     */
    public function withCSRFToken($token)
    {
        $this->csrfToken = $token;

        return $this;
    }

    /**
     * This method is used to build the link once all the information and
     * options have been selected, this will produce a Javascript snippet of
     * code that can be used as an event on a webpage.
     *
     * @return A Javascript snippet that is compliant with plenigo's Javascript
     * SDK.
     */
    public function build()
    {
        $redURL = $this->loginConfig->getRedirectUri();

        if (is_null($redURL) || !is_string($redURL) || empty($redURL)) {
            return 'plenigo.login();';
        }

        $params = array();

        $params[] = addslashes($this->loginConfig->getRedirectUri());
        $params[] = addslashes($this->loginConfig->getAccessScope());

        if ($this->csrfToken !== null) {
            $params[] = addslashes($this->csrfToken);
        }

        $paramString = "'" . implode("','", $params) . "'";

        return 'plenigo.login(' . $paramString . ');';
    }

}
