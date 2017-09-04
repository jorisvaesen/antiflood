<?php foreach ($logs as $identifier => $_logs): ?>
<table class="purchase_content" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0; padding: 25px 0 0; width: 100%;">
    <tr>
        <th class="purchase_heading" style="border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; padding-bottom: 8px;">
            <p style="box-sizing: border-box; color: #9BA2AB; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin: 0;" align="left"><?= h($identifier) ?></p>
        </th>
        <th class="purchase_heading" style="border-bottom-color: #EDEFF2; border-bottom-style: solid; border-bottom-width: 1px; box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; padding-bottom: 8px;">
            <p class="align-right" style="box-sizing: border-box; color: #9BA2AB; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin: 0;" align="right">&nbsp;</p>
        </th>
    </tr>
    <?php foreach ($_logs as $log): ?>
    <tr>
        <td width="80%" class="purchase_item" style="box-sizing: border-box; color: #74787E; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 15px; line-height: 18px; padding: 10px 0; word-break: break-word;"><?= h($log->ip) ?></td>
        <td class="align-right" width="20%" style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; word-break: break-word;" align="right"><?= h($log->created) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endforeach; ?>
