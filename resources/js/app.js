import './bootstrap';

document.querySelector('[data-toggle-password]')?.addEventListener('click', (event) => {
    const button = event.currentTarget;
    const input = document.getElementById('password');

    if (!input) {
        return;
    }

    const isVisible = input.type === 'text';
    input.type = isVisible ? 'password' : 'text';
    button.textContent = isVisible ? 'SHOW' : 'HIDE';
});
