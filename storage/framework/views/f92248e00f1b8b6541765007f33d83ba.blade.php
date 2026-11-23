    @if ($isDashboard)
        <style>
            .fi-topbar,
            .fi-sidebar,
            .fi-sidebar-close-overlay,
            .fi-topbar-open-sidebar-btn,
            .fi-topbar-close-sidebar-btn,
            .fi-topbar-open-collapse-sidebar-btn,
            .fi-topbar-close-collapse-sidebar-btn {
                display: none !important;
            }
            .fi-main-ctn {
                padding-inline-start: 0 !important;
            }
            /* topbar height is given back to the dashboard */
            .cd-wrap {
                height: calc(100vh - 1.5rem) !important;
            }
        </style>
    @endif                
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