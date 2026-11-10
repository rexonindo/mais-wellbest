    <script>
        document.addEventListener('alpine:initialized', () => {
            const sidebar = Alpine.store('sidebar');
            if (!sidebar) return;

            const group = @js($isProduction ? 'production' : 'default');

            try {
                if (localStorage.getItem('sidebarRoleGroup') !== group) {
                    localStorage.setItem('sidebarRoleGroup', group);
                    group === 'production' ? sidebar.close() : sidebar.open();
                }
            } catch (e) {}
        });
    </script>