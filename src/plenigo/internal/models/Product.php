<?php

namespace plenigo\internal\models;

/**
 * <p>
 * Abstract product class for identification purposes.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
interface Product
{

    /**
     * All classes extending this one must implement
     * a getMap method that returns a map with
     * the corresponding instance values.
     *
     * @return array The map data for the product.
     */
    public function getMap();
}