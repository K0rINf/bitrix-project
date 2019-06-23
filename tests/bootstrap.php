<?php

// Подключение ядра 1С-Битрикс
define ('NOT_CHECK_PERMISSIONS', true);
define ('NO_AGENT_CHECK', true);
$GLOBALS['DBType'] = 'mysql';
//$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../sites/s1' );
//require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
//// Искуственная авторизация в роли админа
//$_SESSION['SESS_AUTH']['USER_ID'] = 1;

require_once __DIR__ . '/../vendor/autoload.php';