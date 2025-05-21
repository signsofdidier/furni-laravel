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

