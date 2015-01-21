<?php

require_once __DIR__ . '/../../../../src/plenigo/PlenigoManager.php';

use plenigo\PlenigoManager;

class PlenigoLoggerStaticHelper
{

    const MSG_STATIC_NOTICE = "Static notice";

    public static function testNoticeAsStatic()
    {
        $localClazz = get_class();
        PlenigoManager::notice($localClazz, self::MSG_STATIC_NOTICE);
    }

}
