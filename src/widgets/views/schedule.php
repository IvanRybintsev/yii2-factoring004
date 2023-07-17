<div<?= !empty($styles) ? ' style="' . $styles . '"' : ''?> id="<?=$blockId?>"></div>
<script type="text/javascript">
    function renderSchedule(container, totalAmount) {
        let schedule = new Factoring004.PaymentSchedule({
            elemId: '<?=$blockId?>',
            totalAmount,
        });

        schedule.renderTo(container);
    }
    window.addEventListener('load', function() {
        let totalAmount = <?=$amount?>;
        const container = document.getElementById('<?=$blockId?>');
        renderSchedule(container, totalAmount);

        <?php if (!empty($paymentTypeId)) { ?>
        jQuery(document).on('renderCart', function(e, json) {
            totalAmount = document.querySelector('.unityre__basket-total-card-head').childNodes[1].data.replaceAll(/[^0-9]/ig, '')
            renderSchedule(container, totalAmount);
        });
        jQuery('[name="Order[payment_type_id]"]').on('change', function() {
            if (event.target.value == <?=$paymentTypeId?>) {
                jQuery(container).show();
            } else {
                jQuery(container).hide();
            }
        });
        <?php } ?>
    });
</script>
