import './bootstrap';


import * as FilePond from 'filepond';

import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
// Enregistre les plugins globalement (une seule fois)

FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

// Exporte FilePond globalement pour l'utiliser dans les vues
window.FilePond = FilePond;

/*document.addEventListener('DOMContentLoaded', () => {
    const inputs = document.querySelectorAll('input[type="file"].filepond');

    inputs.forEach(input => {
        FilePond.create(input, {
            allowMultiple: true,
            maxFiles: 5,
            maxFileSize: '10MB',
        });
    });
});
*/
