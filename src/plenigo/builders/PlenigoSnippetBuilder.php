<?php

namespace plenigo\builders;

require_once __DIR__ . '/../models/SnippetConfig.php';
require_once __DIR__ . '/../models/SnippetType.php';

use plenigo\models\SnippetConfig;
use plenigo\models\SnippetType;

/**
 * Description of PlenigoSnippetBuilder
 *
 * @author sebasoft
 */
class PlenigoSnippetBuilder {

    private $config = null;

    /**
     * 
     * @param SnippetConfig $data
     */
    public function __construct(SnippetConfig $data = null) {
        if (is_null($data)) {
            $this->config = new SnippetConfig();
        } else {
            $this->config = $data;
        }
    }

    /**
     * 
     */
    public function build() {
        $data = $this->config;
        if (is_null($data)) {
            $this->config = new SnippetConfig();
        }
        $res = "";
        $elId = $this->config->getElementId();
        $snipId = $this->config->getSnippetId();
        $loggedOut = $this->config->getLoggedOutRedirectUrl();
        $login = $this->config->getLoginToken();
        if (is_null($elId)) {
            $elId = "plenigoSnippet" . substr(md5(uniqid(mt_rand(), true)), 3, 6);
            $res.='<div id="' . $elId . '"></div>\n';
        }
        if (is_null($snipId)) {
            $snipId = SnippetType::PERSONAL_DATA;
        }
        if (is_null($loggedOut)) {
            $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "www.google.com") . '/';
            $loggedOut = $root;
        }
        $loginAddon = "";
        if (!is_null($login)) {
            $loginAddon.=',"' . $login . '"';
        }

        if ($this->config->getEncloseScript()) {
            $res.='<script type="application/javascript">' . "\n";
        }
        $res.='plenigo.renderSnippet("' . $elId . '", ' . $snipId . ', "' . $loggedOut . '"' . $loginAddon . ');';
        if ($this->config->getEncloseScript()) {
            $res.= "\n</script>\n\n";
        }

        return $res;
    }

}
