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
    const selectedAreaLabel = document.getElementById('selected-area-label');

    const setSelected = (card, selected) => {
        card.classList.toggle('border-yazaki-red', selected);
        card.classList.toggle('ring-2', selected);
        card.classList.toggle('ring-yazaki-red/30', selected);
        card.classList.toggle('bg-red-50/50', selected);
        card.classList.toggle('border-gray-200', !selected);
        card.classList.toggle('bg-white', !selected);
        card.setAttribute('aria-pressed', String(selected));

        const icon = card.querySelector('[data-area-icon]');
        if (icon) {
            icon.classList.toggle('bg-yazaki-red', selected);
            icon.classList.toggle('text-white', selected);
            icon.classList.toggle('bg-gray-100', !selected);
            icon.classList.toggle('text-gray-600', !selected);
        }

        const indicator = card.querySelector('[data-selected-indicator]');
        if (indicator) {
            indicator.classList.toggle('hidden', !selected);
        }
    };

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const slug = card.dataset.slug;
            const name = card.dataset.name;

            cards.forEach(other => setSelected(other, other === card));

            if (proceedBtn && slug) {
                proceedBtn.href = `/audit/5s-standard/${slug}`;
                proceedBtn.removeAttribute('aria-disabled');
                proceedBtn.classList.remove('bg-gray-300', 'cursor-not-allowed', 'pointer-events-none');
                proceedBtn.classList.add('bg-yazaki-red', 'hover:bg-yazaki-red-dark');
            }

            if (selectedAreaLabel && name) {
                selectedAreaLabel.textContent = `Area Terpilih: ${name}`;
                selectedAreaLabel.classList.remove('text-gray-500');
                selectedAreaLabel.classList.add('text-yazaki-red', 'font-bold');
            }
        });

        card.addEventListener('dblclick', () => {
            const slug = card.dataset.slug;
            if (slug) {
                window.location.href = `/audit/5s-standard/${slug}`;
            }
        });
    });

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
