<?php

namespace plenigo\models;

/**
 * Description of SnippetConfig
 *
 * @author sebasoft
 */
class SnippetConfig {

    /**
     * The id of the HTML element the snippet should be rendered into.
     *
     * @var string 
     */
    private $elementId;

    /**
     * The snippet type that should be rendered.
     *
     * @var string
     * @see \plenigo\models\SnippetType
     */
    private $snippetId;

    /**
     * t is strongly recommended that you check if a user is logged in before 
     * showing a snippet. If you don't do this check the user will be redirected
     * to this URL if the user is not logged in.
     * 
     * @var string  
     */
    private $loggedOutRedirectUrl;
    
    /**
     * The login token is the optional fourth parameter. It is only necessary 
     * and mandatory if the plenigo user management is not used but an external 
     * user management.
     *
     * @var string
     */
    private $loginToken;
    
    /**
     * Tell the vuilder if we want the output to be enclosed in script tags.
     * Default is true
     *
     * @var bool
     */
    private $encloseScript;

    /**
     * Constructor with fields
     * 
     * @param string $elementId
     * @param string $snippetId
     * @param string $loggedOutRedirectUrl
     * @param string $loginToken
     * @param bool   $enclose
     */
    public function __construct($elementId=null, $snippetId=null, $loggedOutRedirectUrl=null, $loginToken=null, $enclose=true) {
        $this->elementId = $elementId;
        $this->snippetId = $snippetId;
        $this->loggedOutRedirectUrl = $loggedOutRedirectUrl;
        $this->loginToken = $loginToken;
        $this->encloseScript = $enclose;
    }
    
    public function getElementId() {
        return $this->elementId;
    }

    public function getSnippetId() {
        return $this->snippetId;
    }

    public function getLoggedOutRedirectUrl() {
        return $this->loggedOutRedirectUrl;
    }

    public function getLoginToken() {
        return $this->loginToken;
    }

    public function setElementId($elementId) {
        $this->elementId = $elementId;
    }

    public function setSnippetId($snippetId) {
        $this->snippetId = $snippetId;
    }

    public function setLoggedOutRedirectUrl($loggedOutRedirectUrl) {
        $this->loggedOutRedirectUrl = $loggedOutRedirectUrl;
    }

    public function setLoginToken($loginToken) {
        $this->loginToken = $loginToken;
    }

    public function getEncloseScript() {
        if(!isset($this->encloseScript)){
            $this->encloseScript=true;
        }
        return $this->encloseScript;
    }

    public function setEncloseScript($encloseScript) {
        $this->encloseScript = $encloseScript;
    }

}
