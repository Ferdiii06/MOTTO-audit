document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleBtn.querySelector('.icon-eye')?.classList.toggle('hidden', !isPassword);
            toggleBtn.querySelector('.icon-eye-off')?.classList.toggle('hidden', isPassword);
        });
    }

    const cards = [...document.querySelectorAll('[data-area-card]')];
    const proceedBtn = document.getElementById('btn-proceed');
    const setSelected = (card, selected) => {
        card.classList.toggle('border-yazaki-red', selected);
        card.classList.toggle('bg-yazaki-red-light', selected);
        card.classList.toggle('border-gray-200', !selected);
        card.classList.toggle('bg-white', !selected);
        card.setAttribute('aria-pressed', String(selected));
        const icon = card.querySelector('[data-area-icon]');
        icon?.classList.toggle('bg-yazaki-red-light', selected);
        icon?.classList.toggle('text-yazaki-red', selected);
        icon?.classList.toggle('bg-gray-100', !selected);
        icon?.classList.toggle('text-gray-500', !selected);
    };

    cards.forEach(card => card.addEventListener('click', () => {
        cards.forEach(other => setSelected(other, other === card));
        if (proceedBtn) proceedBtn.disabled = false;
    }));

    const homeToggleBtn = document.getElementById('home-toggle-btn');
    const homeSubmenu = document.getElementById('home-submenu');
    const homeChevronIcon = document.getElementById('home-chevron-icon');

    if (homeToggleBtn && homeSubmenu) {
        homeToggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            homeSubmenu.classList.toggle('hidden');
            if (homeChevronIcon) {
                homeChevronIcon.classList.toggle('rotate-180');
            }
        });
    }
});
