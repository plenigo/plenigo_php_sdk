<?php

namespace plenigo\models;

/**
 * Token Grant Type constants.
 *
 * <p>
 * This class serves as a constant map for
 * token grant types that can be requested
 * from the Plenigo API.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class TokenGrantType
{
    /**
     * Gets the initial authorization.
     */
    const AUTHORIZATION_CODE    = 'authorization_code';

    /**
     * Refreshes the token in case of expiration.
     */
    const REFRESH_TOKEN         = 'refresh_token';
}