    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isDashboard): ?>
        <style>
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
        </style>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>                
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
    </script><?php /**PATH D:\website\mais-wellbest\storage\framework\views/cc7e7606500cb55034059393c4992be0.blade.php ENDPATH**/ ?>