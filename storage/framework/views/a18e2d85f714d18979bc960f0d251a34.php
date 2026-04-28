<link rel="manifest" href="/manifest.json">

<script>
    if ("serviceWorker" in navigator) {
        window.addEventListener("load", function () {
            navigator.serviceWorker.register("/service-worker.js")
                .then(reg => console.log("SW registered:", reg))
                .catch(err => console.error("SW registration failed:", err));
        });
    }
</script>
<?php /**PATH D:\website\mais-wellbest\resources\views\pwa.blade.php ENDPATH**/ ?>