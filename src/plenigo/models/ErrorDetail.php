<?php

namespace plenigo\models;

/**
 * <p>
 * This object represents an invalid parameter that was sent to the plenigo API.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ErrorDetail
{

    /**
     * This is the parameter name.
     */
    private $name = null;

    /**
     * This is the error description that details what went wrong.
     */
    private $description = null;

    /**
     * This constructor creates an error instance with a given error name and description.
     * @param string $errorName The error name
     * @param string $errorDesc The error description
     */
    public function __construct($errorName, $errorDesc)
    {
        $this->name = $errorName;
        $this->description = $errorDesc;
    }

    /**
     * This method returns the name of the parameter.
     * @return The parameter name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * This method returns the detailed error description.
     * @return The error description
     */
    public function getDescription()
    {
        return $this->description;
    }

}