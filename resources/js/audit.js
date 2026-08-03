document.addEventListener('DOMContentLoaded', () => {
    // Password show/hide toggle
    const toggleBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            // Swap icon
            toggleBtn.querySelector('.icon-eye').classList.toggle('hidden', !isPassword);
            toggleBtn.querySelector('.icon-eye-off').classList.toggle('hidden', isPassword);
        });
    }

    // Single-select card logic (radio behavior)
    const cards = document.querySelectorAll('[data-area-card]');
    const proceedBtn = document.getElementById('btn-proceed');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            // Unselect all others
            cards.forEach(c => {
                c.classList.remove('border-yazaki-red', 'bg-yazaki-red-light');
                c.classList.add('border-gray-200', 'bg-white');
                const icon = c.querySelector('div > div');
                if (icon) { icon.classList.remove('bg-yazaki-red-light', 'text-yazaki-red'); icon.classList.add('bg-gray-100', 'text-gray-500'); }
            });
            // Select clicked
            card.classList.remove('border-gray-200', 'bg-white');
            card.classList.add('border-yazaki-red', 'bg-yazaki-red-light');
            const icon = card.querySelector('div > div');
            if (icon) { icon.classList.remove('bg-gray-100', 'text-gray-500'); icon.classList.add('bg-yazaki-red-light', 'text-yazaki-red'); }
            // Enable proceed button
            if (proceedBtn) proceedBtn.disabled = false;
        });
    });
});
