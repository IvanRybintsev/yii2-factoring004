<div<?= !empty($styles) ? ' style="' . $styles . '"' : ''?> id="<?=$blockId?>"></div>
<script type="text/javascript">
    window.addEventListener('load', function() {
        let container = document.getElementById('<?=$blockId?>');
        let totalAmount = <?=$amount?>;

        const schedule = new Factoring004.PaymentSchedule({
            elemId: '<?=$blockId?>',
            totalAmount,
        });

        schedule.renderTo(container);
    });
</script>
