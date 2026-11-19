/**
 * Admin Panel Layout Script
 * Handles sidebar collapse/expand with localStorage persistence,
 * dropdown submenus / flyout menus, and mobile navigation drawer.
 */
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('adminSidebar') || document.querySelector('.sidebar');
    var collapseBtn = document.getElementById('sidebarCollapseBtn');
    var desktopToggleBtn = document.getElementById('sidebarDesktopToggle');
    var mobileBtn = document.getElementById('sidebarMobileToggle');
    var overlay = document.getElementById('sidebarOverlay');

    // Helper: Is sidebar currently collapsed?
    function isSidebarCollapsed() {
        return document.documentElement.classList.contains('sidebar-collapsed') || 
               document.body.classList.contains('sidebar-collapsed');
    }

    // Toggle Desktop Sidebar Collapse
    function toggleSidebarCollapse() {
        var willCollapse = !isSidebarCollapsed();
        if (willCollapse) {
            document.documentElement.classList.add('sidebar-collapsed');
            document.body.classList.add('sidebar-collapsed');
            try { localStorage.setItem('admin_sidebar_collapsed', 'true'); } catch (e) {}
            // Close any open inline submenus for cleaner flyout experience
            document.querySelectorAll('.sidebar-dropdown').forEach(function(d) {
                var submenu = d.querySelector('.submenu');
                if (submenu) {
                    submenu.style.removeProperty('display');
                }
            });
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
            document.body.classList.remove('sidebar-collapsed');
            try { localStorage.setItem('admin_sidebar_collapsed', 'false'); } catch (e) {}
            // Restore open submenus
            document.querySelectorAll('.sidebar-dropdown.open').forEach(function(d) {
                var submenu = d.querySelector('.submenu');
                if (submenu) submenu.style.display = 'block';
            });
        }
    }

    if (collapseBtn) {
        collapseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebarCollapse();
        });
    }

    if (desktopToggleBtn) {
        desktopToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleSidebarCollapse();
        });
    }

    // Sidebar Dropdown Toggles (Settings, Customers, etc.)
    document.querySelectorAll('.sidebar-dropdown').forEach(function(dropdown) {
        var toggleBtn = dropdown.querySelector('.menu-dropdown-toggle');
        var submenu = dropdown.querySelector('.submenu');

        if (toggleBtn && submenu) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // If collapsed on desktop, clicking expands the sidebar and opens dropdown
                if (window.innerWidth > 991 && isSidebarCollapsed()) {
                    toggleSidebarCollapse();
                    dropdown.classList.add('open');
                    submenu.style.display = 'block';
                    toggleBtn.setAttribute('aria-expanded', 'true');
                    return;
                }

                var isOpen = dropdown.classList.contains('open');
                if (isOpen) {
                    dropdown.classList.remove('open');
                    submenu.style.display = 'none';
                    toggleBtn.setAttribute('aria-expanded', 'false');
                } else {
                    dropdown.classList.add('open');
                    submenu.style.display = 'block';
                    toggleBtn.setAttribute('aria-expanded', 'true');
                }
            });
        }
    });

    // Mobile Sidebar Toggle and Backdrop Overlay
    var mobileCloseBtn = document.getElementById('sidebarMobileCloseBtn');
    function closeMobileSidebar() {
        if (sidebar) sidebar.classList.remove('mobile-open');
        if (overlay) overlay.classList.remove('active');
        document.body.classList.remove('mobile-sidebar-active');
    }

    if (mobileBtn && sidebar) {
        mobileBtn.addEventListener('click', function() {
            var isOpen = sidebar.classList.contains('mobile-open');
            if (isOpen) {
                closeMobileSidebar();
            } else {
                sidebar.classList.add('mobile-open');
                if (overlay) overlay.classList.add('active');
                document.body.classList.add('mobile-sidebar-active');
            }
        });
    }

    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            closeMobileSidebar();
        });
    }

    // Keyboard ESC to close mobile sidebar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar && sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.remove('active');
            document.body.classList.remove('mobile-sidebar-active');
        }
    });

    // Auto cleanup mobile open state on desktop resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991 && sidebar && sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
            if (overlay) overlay.classList.remove('active');
            document.body.classList.remove('mobile-sidebar-active');
        }
    });

    // Sync active state on submenu items
    function syncActiveSubmenuItems() {
        var currentPath = window.location.pathname;
        document.querySelectorAll('.submenu .submenu-item').forEach(function(item) {
            var itemHref = item.getAttribute('href') || '';
            try {
                var urlObj = new URL(itemHref, window.location.origin);
                if (urlObj.pathname === currentPath) {
                    item.classList.add('active');
                }
            } catch (e) {}
        });
    }

    syncActiveSubmenuItems();

    // ==========================================
    // Universal Password Eye Toggle Buttons
    // ==========================================
    document.querySelectorAll('.pwd-toggle-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });
    });
});

