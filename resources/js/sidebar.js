document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (window.innerWidth < 768) {
                document.body.classList.toggle('sidebar-toggled');
            } else {
                document.body.classList.toggle('mini-sidebar');
            }
        });
    }

    // Fechar ao redimensionar se necessário
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            document.body.classList.remove('sidebar-toggled');
        }
    });
});