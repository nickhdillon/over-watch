import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import anchor from '@alpinejs/anchor';
import '@wotz/livewire-sortablejs';
import selectableList from './selectable-list.js';

Alpine.plugin(anchor);

Alpine.directive('responsive-sortable-handle', (el, {}, { cleanup }) => {
    const mobile = window.matchMedia('(max-width: 639px)');
    let frame;

    const syncHandle = () => {
        cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            el.livewire_sortable?.option(
                'handle',
                mobile.matches ? '[wire\\:sortable\\.handle], [wire\\:sortable-group\\.handle]' : null,
            );
        });
    };

    syncHandle();
    mobile.addEventListener('change', syncHandle);

    cleanup(() => {
        cancelAnimationFrame(frame);
        mobile.removeEventListener('change', syncHandle);
    });
});
 
Alpine.data('selectableList', selectableList);

window.Cropper = Cropper;

Livewire.start();
