<?php

namespace plenigo\internal\serverInterface\oauth2;

require_once __DIR__ . '/../ServerInterface.php';

use \plenigo\internal\serverInterface\ServerInterface;

/**
 * Verify
 *
 * <p>
 * The OAuth2 verification parameters class.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalServerInterfaceOauth2
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class Verify extends ServerInterface
{

    /**
     * The access grant type.
     */
    protected $grant_type;

    /**
     * The access code.
     */
    protected $code;

    /**
     * The redirect URI originally privided.
     */
    protected $redirect_uri;

    /**
     * The client ID.
     */
    protected $client_id;

    /**
     * The client secret.
     */
    protected $client_secret;

    /**
     * The state/CSRF Token.
     */
    protected $state;

    /**
     * The constructor for the Verify class.
     *
     * @param array $map The array map with the Verify data.
     *
     * @return The Verify instance.
     */
    public function __construct(array $map = array())
    {
        $this->setValuesFromMap($map);
    }

    /**
     * Accepts a map of key/values pairs to insert into
     * itself.
     *
     * @param array $map The array map to use for value insertion.
     *
     * @return void.
     */
    public function setValuesFromMap(array $map)
    {
        $this->setValueFromMapIfNotEmpty('grant_type', $map);
        $this->setValueFromMapIfNotEmpty('code', $map);
        $this->setValueFromMapIfNotEmpty('redirect_uri', $map);
        $this->setValueFromMapIfNotEmpty('client_id', $map);
        $this->setValueFromMapIfNotEmpty('client_secret', $map);
        $this->setValueFromMapIfNotEmpty('state', $map);
    }

}