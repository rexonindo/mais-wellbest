<script>
    document.addEventListener('focus-in-qty', () => {
        const input = document.querySelector('[wire\\:model="data.in_qty"]');
        if (input) {
            input.focus();
            input.select();
        }
    });
</script>
<?php /**PATH D:\website\mais-wellbest\resources\views/js-focus-in-qty.blade.php ENDPATH**/ ?>