<script type="text/javascript">
    window.onload = function() {
        let container = document.getElementById('<?=$blockId?>');
        let totalAmount = <?=$amount?>;

        const schedule = new Factoring004.PaymentSchedule({
            elemId: '<?=$blockId?>',
            totalAmount,
        });

        schedule.renderTo(container);
    }
</script>
<div<?= !empty($styles) ? ' style="' . $styles . '"' : ''?> id="<?=$blockId?>"></div>
