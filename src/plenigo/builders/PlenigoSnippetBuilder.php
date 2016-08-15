<?php

namespace plenigo\builders;

require_once __DIR__ . '/../models/SnippetConfig.php';

use plenigo\models\SnippetConfig;

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
        $res = "";
        $elId = $this->config->getElementId();
        if (is_null($elId)) {
            $elId = "plenigoSnippet" . substr(md5(uniqid(mt_rand(), true)), 3, 6);
            $res.='<div id="' . $elId . '"></div>\n';
        }
        

        return $res;
    }

}
