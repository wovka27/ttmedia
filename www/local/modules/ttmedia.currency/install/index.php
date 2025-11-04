<?php

use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Ttmedia\Currency\CourseTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

class ttmedia_currency extends CModule
{
    public $MODULE_ID = 'ttmedia.currency';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    private array $models;

    public function __construct()
    {
        $this->models = [
            CourseTable::class,
        ];
        $version = include __DIR__.'/version.php';

        $this->MODULE_VERSION = $version['VERSION'];
        $this->MODULE_VERSION_DATE = $version['VERSION_DATE'];

        $this->MODULE_NAME = Loc::getMessage('CURRENCY_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CURRENCY_MODULE_DESC');
        $this->PARTNER_NAME = 'TTMedia';
        $this->PARTNER_URI = 'https://ttmedia.ru';
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallFiles();
        $this->InstallDB();
    }

    public function DoUninstall(): void
    {
        global $APPLICATION, $step;
        $step = intval($step);

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage('CURRENCY_MODULE_UNINSTALL', ['NAME' => $this->MODULE_NAME]),
                dirname(__DIR__, 3)."/modules/$this->MODULE_ID/install/unstep.php",
            );
        } else {
            if ($step == 2) {
                $this->UnInstallFiles();
                $this->UnInstallDB();
                ModuleManager::unRegisterModule($this->MODULE_ID);
            }
        }
    }

    public function InstallDB(): void
    {
        self::includeModule($this->MODULE_ID);

        $db = Application::getConnection();

        foreach ($this->models as $model) {
            if (method_exists($model, 'getEntity')) {
                $entity = $model::getEntity();
                if (!$db->isTableExists($entity->getDBTableName())) {
                    $entity->createDbTable();
                }
            }
        }
    }

    public function UnInstallDB(): void
    {
        $ctx = \Bitrix\Main\Context::getCurrent()->getRequest();
        $saveData = $ctx->get('savedata');

        if ($saveData == 'Y') {
            return;
        }

        self::includeModule($this->MODULE_ID);

        $db = Application::getConnection();

        foreach ($this->models as $model) {
            if (method_exists($model, 'getEntity')) {
                $entity = $model::getEntity();
                $tableName = $entity->getDBTableName();

                if ($db->isTableExists($tableName)) {
                    $db->dropTable($tableName);
                }
            }
        }
    }

    public function InstallFiles(): void
    {
        CopyDirFiles(
            __DIR__."/components/currency",
            dirname(__DIR__, 3) . "/components/currency",
            true,
            true,
        );
    }

    public function UnInstallFiles(): void
    {
        DeleteDirFilesEx("components/currency", dirname(__DIR__, 3));
    }
}
