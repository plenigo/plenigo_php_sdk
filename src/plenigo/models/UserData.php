<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/models/Address.php';
require_once __DIR__ . '/Model.php';

use plenigo\internal\models\Address;
//use plenigo\models\Model;

/**
 * UserData
 *
 * <p>
 * User Data model that mirrors the information provided by
 * the plenigo REST API.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class UserData extends Model {


    /**
     * @param string $id
     */
    public function setId($id) {
        $this->data['userId'] = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->data['userId'] ?: '';
    }

    /**
     * @return Address address of the user
     */
    public function getAddress() {
        return Address::createFromMap($this->data);
    }

    /**
     * Creates a UserData instance using the provided map
     * properties.
     *
     * @param array $map The map with the properties to pass onto UserData.
     *
     * @return Model UserData instance.
     */
    public static function  createFromMap(array $map) {
        return new self($map);
    }
}
