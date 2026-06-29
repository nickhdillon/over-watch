import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import anchor from '@alpinejs/anchor';
import '@wotz/livewire-sortablejs';

Alpine.plugin(anchor);

window.Cropper = Cropper;

Livewire.start();
