<?php if ($clientRoute == 'redirect') { ?>
    <a href="<?=$action?>" class="<?=$buttonClass?>" target="_blank">Оплатить</a>
<?php } else { ?>
    <a href="#" onclick="showPaymentWidget('<?=$action?>')" class="<?=$buttonClass?>" >Оплатить</a>
<?php } ?>