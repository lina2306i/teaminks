
js + meme html / css
/*
We want to preview images, so we need to register the Image Preview plugin
*/
FilePond.registerPlugin(

	// encodes the file as base64 data
  FilePondPluginFileEncode,

	// validates the size of the file
	FilePondPluginFileValidateSize,

	// corrects mobile image orientation
	FilePondPluginImageExifOrientation,

	// previews dropped images
  FilePondPluginImagePreview
);

// Select the file input and use create() to turn it into a pond
FilePond.create(
	document.querySelector('input')
);

---------------------------------------------
v1
<!-- Attachments Section -->
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

                        <div class="mb-5">
                            <div id="attachments" class="  bg-gray-700 border border-gray-600 rounded-xl">

                                    <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
                                    <p class="text-lg font-bold mb-3">Drag &&& drop files here</p>
                                    <p class="text-sm text-gray-400">or <span class="text-primary underline">click to browse</span></p>
                                    <p class="text-xs mt-3 text-gray-500">
                                        Max 10MB per file • Images, PDF, Office, ZIP, video...
                                    </p>
                                    <hr>
                                    <!-- FilePond attachments max-files="5"-->
                                    <!--
                                        <input type="file" name="attachments[]" id="attachments"  multiple
                                     >
                                    The classic file input element we'll enhance
                                    to a file pond, configured with attributes
                                    -->
                                    <input type="file"
                                        class="filepond"
                                        name="attachments[]"  id="attachments"
                                        multiple
                                        data-allow-reorder="true"
                                        data-max-file-size="10MB"
                                        data-max-files="5">

                            </div>

                            @error('attachments.*')
                                <div class="text-danger small mt-4">{{ $message }}</div>
                            @enderror
                        </div>


@push('scripts')



    <!-- v1 : classique sans js et marche Scriipt v2 :: Alpine.js pour gérer les uploads en Ajax :: Version finale avec attachments + Ajax -->
    <!--// Vanilla JS pour drag & drop + sélection script v3 puis v4 -->

<link href="https://unpkg.com/filepond@^5/dist/filepond.css" rel="stylesheet">
<script src="https://unpkg.com/filepond@^5/dist/filepond.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.js"></script>

<script>
    // 1. enregistrer tous les plugins que tu veux utiliser
    FilePond.registerPlugin(
        FilePondPluginFileEncode,
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview ,
    );

    // créer FilePond
    // Initialiser FilePond    FilePond.registerPlugin(
   // const inputElement = document.querySelector('#attachments');
 //FilePond.create(inputElement, {

 FilePond.create(
    document.querySelector('#attachments'), {
    //	document.querySelector('input')

        //FilePond.create(document.querySelector('#attachments'), {
    allowMultiple: true,
    maxFiles: 5,
    maxFileSize: '10MB',
    acceptedFileTypes: [
        'image/jpeg', 'image/png', 'image/gif', 'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain', 'application/zip', 'video/mp4'
    ],
    server: {
        process: '{{ route("leader.filepond.upload") }}',
        revert: '{{ route("leader.filepond.revert") }}',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    }
});

/*
We want to preview images, so we need to register the Image Preview plugin
*/
FilePond.registerPlugin(

	// encodes the file as base64 data
  FilePondPluginFileEncode,

	// validates the size of the file
	FilePondPluginFileValidateSize,

	// corrects mobile image orientation
	FilePondPluginImageExifOrientation,

	// previews dropped images
  FilePondPluginImagePreview
);

// Select the file input and use create() to turn it into a pond
FilePond.create(
	document.querySelector('input')
);

</script>

@endpush

@push('styles')
    <style>
        /**
 * FilePond Custom Styles
 */
.filepond--drop-label {
	color: #4c4e53;
}

.filepond--label-action {
	text-decoration-color: #babdc0;
}

.filepond--panel-root {
	border-radius: 2em;
	background-color: #edf0f4;
}

.filepond--item-panel {
	background-color: #595e68;
}

.filepond--drip-blob {
	background-color: #7f8a9a;
}


/

    </style>
@endpush
---------------------------------------------------------------------------

v2 js


@push('scripts')



    <!-- v1 : classique sans js et marche Scriipt v2 :: Alpine.js pour gérer les uploads en Ajax :: Version finale avec attachments + Ajax -->
    <!--// Vanilla JS pour drag & drop + sélection script v3 puis v4 -->

<!-- FilePond CSS -->
<link href="{{ asset('vendor/filepond/filepond.css') }}" rel="stylesheet">

<!-- FilePond JS + Plugins -->
<script type="module">
    import FilePond from '{{ asset('vendor/filepond/filepond.esm.js') }}';
    import FilePondPluginFileValidateSize from '{{ asset('vendor/filepond/filepond-plugin-file-validate-size.esm.js') }}';
    import FilePondPluginFileValidateType from '{{ asset('vendor/filepond/filepond-plugin-file-validate-type.esm.js') }}';
    import FilePondPluginImagePreview from '{{ asset('vendor/filepond/filepond-plugin-image-preview.esm.js') }}';

    // Register plugins
    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
    );

    // Set default options (dark style + limits)
    FilePond.setOptions({
        allowMultiple: true,
        allowReorder: true,
        maxFiles: 5,
        maxFileSize: '10MB',
        labelIdle: `
            <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6 block"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm text-gray-400">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">Max 10MB per file • Images, PDF, Office, ZIP, video...</p>
        `,
        labelFileProcessing: 'Uploading...',
        labelFileProcessingComplete: 'Uploaded',
        labelTapToCancel: 'Tap to cancel',
        labelTapToUndo: 'Tap to undo',
    });

    // Create FilePond instance
    const pond = FilePond.create(document.querySelector('#attachments'));
</script>


@endpush

@push('styles')
    <style>
        /**
 * FilePond Custom Styles
 */
 /* FilePond Dark Theme Custom Styles */
.filepond--root {
    background-color: #1f2937; /* bg-gray-800 */
    color: #e5e7eb; /* text-gray-200 */
    font-family: inherit;
    border-radius: 1rem;
}
.filepond--drop-label {
	color: #9ca3af;   /* 4c4e53  */
}

.filepond--label-action {
	text-decoration-color: #babdc0; /* babdc0 */
    color: #3b82f6; /* text-primary */
    text-decoration: underline;
}

.filepond--panel-root {
	border-radius: 1em;  /* 2rm    edf0f4 */
	background-color: #374151;
}

.filepond--item-panel {
	background-color: #4b5563; /* bg-gray-600 */ /*  595e68*/
}

.filepond--drip-blob {
	background-color: #6b7280; /* gray-500 */  /* 7f8a9a */
}
.filepond--file-info {
    color: #d1d5db; /* text-gray-300 */
}

.filepond--file-action-button {
    background-color: #374151;
    color: #e5e7eb;
}

.filepond--progress-indicator {
    color: #3b82f6; /* primary */
}


    </style>
@endpush

---------------------------------------------------------------------------
v3


<!-- Attachments Section -->
                        <!-- Attachments Section -->
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

                        <div class="mb-5">
                            <div id="attachments" class="  bg-gray-700 border border-gray-600 rounded-xl">

                                    <p class="text-lg font-bold mb-3">Drag drop files here</p>
                                    <p class="text-sm text-gray-400">or <span class="text-primary underline">click to browse</span></p>

                                    <!-- FilePond attachments max-files="5" id="attachments"
                                        accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,video/mp4">
                                        accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/zip,video/mp4"
                                    <input type="file" name="attachments[]" multiple class="form-control">
                                    -->
                                    <input type="file"
                                        class="filepond"
                                        name="attachments[]"
                                        multiple
                                        data-allow-reorder="true"
                                        data-max-file-size="10MB"
                                        data-max-files="5"
                                        data-allow-process="false"
                                        data-allow-revert="false"
                                        > <!-- IMPORTANT : désactive tout processing --> <!-- désactive revert aussi -->
                            </div>
                            <small class="text-gray-400 d-block mt-3">
                                <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
                                &&& Max 5 files • 10MB per file • Images, PDF, Office, ZIP, video...
                            </small>
                            @error('attachments.*')
                                <div class="text-danger small mt-4">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Section FilePond dans votre formulaire claude--}}
                        <div class="mb-5">
                            <label class="text-lg font-bold mb-3 d-block">Pièces jointes (Max 5 fichiers)</label>

                            <div id="attachments-container" class="bg-gray-700 border border-gray-600 rounded-xl p-4">
                                {{-- Input FilePond --}}
                                <input type="file"
                                    class="filepond"
                                    id="filepond-input"
                                    name="filepond_files[]"
                                    multiple
                                    data-allow-reorder="true"
                                    data-max-file-size="10MB"
                                    data-max-files="5">
                            </div>

                            <small class="text-gray-400 d-block mt-3">
                                <i class="fas fa-cloud-upload-alt me-2"></i>
                                Max 5 files • 10MB per file • Images, PDF, Office, ZIP, video...
                            </small>

                            @error('attachments.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>



@push('styles')
<!-- FilePond CSS -->
<link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- FilePond JS + Plugins claude-- -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enregistrer les plugins FilePond
    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
    );

    // Créer l'instance FilePond
    const pond = FilePond.create(document.querySelector('#filepond-input'), {
        allowMultiple: true,
        allowReorder: true,
        maxFiles: 5,
        maxFileSize: '10MB',
        acceptedFileTypes: [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain',
            'application/zip',
            'video/mp4'
        ],
        labelIdle: `
            <div class="text-center py-4">
                <i class="fas fa-cloud-upload-alt fa-4x text-gray-400 mb-3"></i>
                <p class="text-lg font-bold mb-2">Glissez-déposez vos fichiers ici</p>
                <p class="text-sm text-gray-400">ou <span class="text-primary" style="text-decoration: underline;">cliquez pour parcourir</span></p>
                <p class="text-xs mt-3 text-gray-500">Max 5 fichiers • 10MB par fichier</p>
            </div>
        `,
        labelFileProcessing: 'Préparation...',
        labelFileProcessingComplete: 'Prêt',
        labelTapToCancel: 'Cliquer pour annuler',
        labelTapToUndo: 'Cliquer pour annuler',
        credits: false,

        // ✅ IMPORTANT : Pas de serveur, mode client uniquement
        allowProcess: false,
        instantUpload: false,
    });

    // 🔥 SOLUTION : Avant la soumission du formulaire,
    // créer des inputs cachés avec les vrais fichiers
    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', function(e) {
            // Récupérer tous les fichiers de FilePond
            const files = pond.getFiles();

            // Si aucun fichier, laisser passer
            if (files.length === 0) {
                return true;
            }

            // Créer un DataTransfer pour gérer les fichiers
            const dataTransfer = new DataTransfer();

            // Ajouter chaque fichier au DataTransfer
            files.forEach(fileItem => {
                if (fileItem.file) {
                    dataTransfer.items.add(fileItem.file);
                }
            });

            // Créer ou mettre à jour l'input caché avec les vrais fichiers
            let realInput = document.querySelector('input[name="attachments[]"]');

            if (!realInput) {
                realInput = document.createElement('input');
                realInput.type = 'file';
                realInput.name = 'attachments[]';
                realInput.multiple = true;
                realInput.style.display = 'none';
                form.appendChild(realInput);
            }

            // Assigner les fichiers à l'input réel
            realInput.files = dataTransfer.files;

            console.log('Fichiers envoyés:', realInput.files.length);
        });
    }
});
</script>
@endpush



@push('scripts')
    <!-- v1 : classique sans js et marche Scriipt v2 :: Alpine.js pour gérer les uploads en Ajax :: Version finale avec attachments + Ajax -->
    <!--// Vanilla JS pour drag & drop + sélection script v3 puis v4 -->

<!-- FilePond CSS -->

<!-- FilePond JS + Plugins -->

<scrip type="module">
    FilePond.registerPlugin(
        FilePondPluginFileValidateSize,
        FilePondPluginFileValidateType,
        FilePondPluginImagePreview
    );
    // Options globales FilePond
    FilePond.setOptions({
        allowMultiple: true,
        allowReorder: true,
        maxFiles: 5,
        maxFileSize: '10MB',
        allowProcess: false,   // ← Crucial : pas d'upload Ajax
        allowRevert: false,    // ← Pas de suppression Ajax
        labelIdle: `
            <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6 block"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm text-gray-400">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">*** Max 10MB per file • Images, PDF, Office, ZIP, video...</p>
        `,
        /*labelFileProcessing: 'Preparing...',
        labelFileProcessingComplete: 'Ready',
        labelTapToCancel: 'Tap to cancel',
        labelTapToUndo: 'Tap to undo',*/

        // Pas de 'server' → client-only
    });

    // Crée FilePond sur l'input
    FilePond.create(document.querySelector('.filepond'));
</script>

@endpush

@push('styles')
    <style>
        /**
 * FilePond Custom Styles
        claude
 */
 /* FilePond Dark Theme Custom Styles */
.filepond--root {
    background-color: #1f2937; /* bg-gray-800 */
    color: #e5e7eb; /* text-gray-200 */
    font-family: inherit;
    border-radius: 1rem;
}
.filepond--drop-label {
	color: #9ca3af;   /* 4c4e53  */
}

.filepond--label-action {
	text-decoration-color: #babdc0; /* babdc0 */
    color: #3b82f6; /* text-primary */
    text-decoration: underline;
}

.filepond--panel-root {
	border-radius: 1em;  /* 2rm    edf0f4 */
	background-color: #374151;
}

.filepond--item-panel {
	background-color: #4b5563; /* bg-gray-600 */ /*  595e68*/
}

.filepond--drip-blob {
	background-color: #6b7280; /* gray-500 */  /* 7f8a9a */
}
.filepond--file-info {
    color: #d1d5db; /* text-gray-300 */
}

.filepond--file-action-button {
    background-color: #374151;
    color: #e5e7eb;
}

.filepond--progress-indicator {
    color: #3b82f6; /* primary */
}


    </style>
@endpush
