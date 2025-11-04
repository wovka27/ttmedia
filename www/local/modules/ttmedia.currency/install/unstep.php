<?php
/* @var $APPLICATION */

IncludeModuleLangFile(__FILE__);
?>

<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANG; ?>">
    <input type="hidden" name="id" value="ttmedia.currency">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <p>
        <input type="checkbox" name="savedata" id="savedata" value="Y" checked>
        <label for="savedata"><?= GetMessage('CURRENCY_MODULE_UNSTEP_SAVE_DATA'); ?></label>
    </p>
    <input type="submit" name="inst" value="<?= GetMessage('CURRENCY_MODULE_UNSTEP_DEL'); ?>">
</form>