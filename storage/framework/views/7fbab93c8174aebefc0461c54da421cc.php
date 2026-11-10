    <script>
        document.addEventListener('alpine:initialized', () => {
            const sidebar = Alpine.store('sidebar');
            if (!sidebar) return;

            const group = <?php echo \Illuminate\Support\Js::from($isProduction ? 'production' : 'default')->toHtml() ?>;

            try {
                if (localStorage.getItem('sidebarRoleGroup') !== group) {
                    localStorage.setItem('sidebarRoleGroup', group);
                    group === 'production' ? sidebar.close() : sidebar.open();
                }
            } catch (e) {}
        });
    </script><?php /**PATH D:\website\mais-wellbest\storage\framework\views/15e157cd4f17c6b36b571aa460a7fb6c.blade.php ENDPATH**/ ?>