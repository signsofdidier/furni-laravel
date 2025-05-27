import './bootstrap';

// index.js
import 'preline'

// Luister naar het Livewire browser event
window.addEventListener('alert', event => {
    livewireAlert.fire({
        icon: event.detail.type || 'success',
        title: event.detail.title || '',
        text: event.detail.text || '',
        timer: event.detail.timer || 3000,
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
    });
});


document.addEventListener('livewire:load', () => {
    Livewire.hook('message.processed', (message, component) => {
        // Forceer heractivatie van alle open collapses
        const collapseEls = document.querySelectorAll('.accordion-collapse.collapse');
        collapseEls.forEach(el => {
            if (el.classList.contains('show')) {
                // Bootstrap herinitialiseren
                new bootstrap.Collapse(el, {
                    toggle: false
                });
            }
        });
    });
});

