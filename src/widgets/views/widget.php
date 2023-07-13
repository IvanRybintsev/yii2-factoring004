<div id='modal-bnplpayment'></div>
<script type="text/javascript">
    function showPaymentWidget(path) {
        fetch(path,{
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then((result) => {
                if (result.redirectErrorPage) {
                    return window.location.replace(result.redirectErrorPage)
                }
                const bnplKzApi = new BnplKzApi.CPO(
                    {
                        rootId: 'modal-bnplpayment',
                        callbacks: {
                            onError: () => window.location.replace(result.redirectLink),
                            onDeclined: () => window.location.replace('/'),
                            onEnd: () => window.location.replace('/')
                        }
                    });
                bnplKzApi.render({
                    redirectLink: result.redirectLink
                });
            })
            .catch((err) => {
                window.location.href = window.location.replace('/');
            })
    }
</script>