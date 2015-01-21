<?php

namespace plenigo\internal\exceptions;

/**
 * <p>
 * Exception class for {@link plenigo\models\Product} class.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalExceptions
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 *
 */
class ProductException extends \Exception
{
    /**
     * The exception message
     */
    protected $message  = 'Not a valid instance of plenigo\Product.';

    /**
     * The exception status code
     */
    protected $code     = 100;
}