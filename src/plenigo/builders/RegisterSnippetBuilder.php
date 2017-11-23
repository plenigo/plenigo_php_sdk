<?php

namespace plenigo\builders;

require_once __DIR__ . '/../models/AccessScope.php';
require_once __DIR__ . '/../models/LoginConfig.php';

use plenigo\PlenigoManager;

/**
 * RegisterSnippetBuilder
 *
 * <p>
 * This class builds a plenigo's Javascript API register function that is
 * compliant.
 * </p>
 *
 * @category SDK
 * @package  PlenigoBuilders
 * @author   plenigo GmbH
 * @link     https://www.plenigo.com
 * @see      https://plenigo.github.io/sdks/javascript#registration---open-the-plenigo-registration-window
 */
class RegisterSnippetBuilder
{

    /**
     * The data required to build the snippet.
     */
    private $config;

    private $id;

    /**
     * <p>
     * Constructor of the snippet builder
     * </p>
     *
     * <p>
     * If no LoginConfig is passed, the SDK will try to register the user without using single sign on.
     * </p>
     *
     * @param array $config config-data for the register function
     */
    public function __construct(array $config = array())
    {
        $this->config = $config;

        // create a unique id
        $this->id = uniqid('plenigoRegisterConfig');
    }

    /**
     * Get the configOptions as Javascript Object
     *
     * @param bool $wrapWithScriptTag
     * @return string
     */
    public function writeOptions($wrapWithScriptTag = true) {

        // set global testmode if its not set
        $this->config['testMode'] = isset($this->config['testMode']) ? $this->config['testMode'] : PlenigoManager::get()->isTestMode();

        // build the object
        $configObject = "{$this->id} = " . json_encode($this->config);

        // wrap it
        if ($wrapWithScriptTag) {

            $configObject = "<script>\n\n" . $configObject . ";\n\n</script>";

        }

        return $configObject;
    }

    /**
     * This method is used to build the link once all the information and
     * options have been selected, this will produce a Javascript snippet of
     * code that can be used as an event on a webpage.
     *
     * @return string A Javascript snippet that is compliant with plenigo's Javascript
     * SDK.
     */
    public function build()
    {

        return "plenigo.registration({$this->id});";
    }

}
