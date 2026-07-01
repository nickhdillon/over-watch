import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import anchor from '@alpinejs/anchor';
import '@wotz/livewire-sortablejs';
import selectableList from './selectable-list.js';

Alpine.plugin(anchor);
 
Alpine.data('selectableList', selectableList);

window.Cropper = Cropper;

Livewire.start();
