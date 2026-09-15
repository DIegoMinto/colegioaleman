import './bootstrap';
import '../css/app.css';
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-eliminar');
    if (!btn) return;

    e.preventDefault();

    window.dispatchEvent(new CustomEvent('abrir-modal', {
        detail: {
            title: btn.dataset.title || '¿Confirmar acción?',
            message: btn.dataset.message || '¿Está seguro de continuar?',
            url: btn.dataset.url || '#'
        }
    }));
});

document.addEventListener('up:fragment:inserted', () => {
    Alpine.initTree(document.body);
});

Alpine.start();