<?php

namespace plenigo\models;

/**
 * TokenData
 *
 * <p>
 * This class contains the returned
 * access token information.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class TokenData
{
    /**
     * The response's access token.
     */
    private $accessToken;

    /**
     * The time the token will expire in.
     */
    private $expiresIn;

    /**
     * The refresh token information if provided.
     */
    private $refreshToken;

    /**
     * The CSRF Token returned by the request.
     */
    private $state;

    /**
     * The access type for this token.
     */
    private $tokenType;

    /**
     * This constructor initializes the TokenData and returns an instace.
     *
     * @param string $accessToken  The returned access token.
     * @param string $expiresIn    The time the token will expire in.
     * @param string $refreshToken The refresh token if provided.
     * @param string $state        The CSRF Token returned by the request.
     * @param string $tokenType    The access type for this token.
     *
     * @return TokenData instance.
     */
    public function __construct($accessToken, $expiresIn, $refreshToken, $state, $tokenType)
    {
        $this->accessToken  = $accessToken;
        $this->expiresIn    = $expiresIn;
        $this->refreshToken = $refreshToken;
        $this->state        = $state;
        $this->tokenType    = $tokenType;
    }

    /**
     * Gets the access token.
     *
     * @return The access token.
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Gets the expiration date.
     *
     * @return The expiration date.
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * Gets the refresh token.
     *
     * @return The refresh token.
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Gets the CSRF Token originally used for the request
     * and returned by the response.
     *
     * @return The CSRF Token.
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Gets the access type for the token.
     *
     * @return The access type.
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * Creates a TokenData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return TokenData instance.
     */
    public static function createFromMap(array $map)
    {
        $accessToken    = isset($map['access_token']) ? $map['access_token'] : null;
        $expiresIn      = isset($map['expires_in']) ? $map['expires_in'] : null;
        $refreshToken   = isset($map['refresh_token']) ? $map['refresh_token'] : null;
        $state          = isset($map['state']) ? $map['state'] : null;
        $tokenType      = isset($map['token_type']) ? $map['token_type'] : null;

        return new TokenData($accessToken, $expiresIn, $refreshToken, $state, $tokenType);
    }
}