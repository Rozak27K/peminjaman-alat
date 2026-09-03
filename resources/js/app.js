const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const sidebarToggle = document.getElementById('sidebarToggle');
const clock = document.getElementById('clock');
const quickSearch = document.getElementById('quickSearch');

function toggleSidebar(forceOpen = null) {
    if (! sidebar || ! overlay) {
        return;
    }

    const willOpen = forceOpen ?? sidebar.classList.contains('-translate-x-full');

    sidebar.classList.toggle('-translate-x-full', ! willOpen);
    overlay.classList.toggle('hidden', ! willOpen);
}

sidebarToggle?.addEventListener('click', () => toggleSidebar());
overlay?.addEventListener('click', () => toggleSidebar(false));

quickSearch?.addEventListener('keydown', (event) => {
    if (event.key !== 'Enter') {
        return;
    }

    const value = quickSearch.value.toLowerCase().trim();
    const menuMap = JSON.parse(quickSearch.dataset.menuMap ?? '{}');
    const target = Object.entries(menuMap).find(([key]) => value.includes(key));

    if (target) {
        window.location.href = target[1];
    }
});

window.setInterval(() => {
    if (! clock) {
        return;
    }

    clock.textContent = new Date().toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
    });
}, 1000);
