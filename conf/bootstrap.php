<?php

namespace zmultilang\conf;

include(__DIR__ . '/../src/classes/resources/Injector.php');
include(__DIR__ . '/../src/classes/ZMultiLang.php');
include(__DIR__ . '/../src/classes/helpers/interfaces/ILocale.php');
include(__DIR__ . '/../src/classes/helpers/NoLocale.php');
include(__DIR__ . '/../src/classes/models/LocaleSwitch.php');
include(__DIR__ . '/../src/classes/helpers/ZfwrkCustoms.php');
include(__DIR__ . '/../src/classes/helpers/ZfwrkLocales.php');
include(__DIR__ . '/../src/classes/translations/ZfwrkTranslations.php');
include(__DIR__ . '/../src/classes/languages/ZfwrkLanguages.php');

use zmultilang\src\classes\resources\Injector;

/**
 * @return Injector
 */
return new Injector(__DIR__ . '/index.php');
