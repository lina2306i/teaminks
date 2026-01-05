fonction store #

        // Gestion des attachments v0
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tasks/' . $task->id, 'public');

                $task->attachments()->create([
                    'filename'     => $file->getClientOriginalName(),
                    'path'         => $path,
                    'mime_type'    => $file->getMimeType(),
                    'size'         => $file->getSize(),
                    'uploaded_by'  => Auth::id(),
                ]);
            }

            $task->update(['attachments_count' => $task->attachments()->count()]);
        }
        // Si tu veux aussi gérer les attachments uploadés via Ajax ---- - (tu peux les récupérer via une colonne temporaire ou via session)  v1
       /* if ($request->has('attachments')) {
            $task->attachments = $request->input('attachments');
            $task->save();
        }*/

----------------------------
v1 avec Dropzone via CDN
1. Ajoute Dropzone via CDN (dans ton layout appW.blade.php, dans <head> et avant </body>)

blade<!-- Dans <head> -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@6/dist/dropzone.css">

<!-- Avant </body> -->
<script src="https://unpkg.com/dropzone@6/dist/dropzone-min.js"></script>

2. Remplace toute la section Attachments par ça
*****************************************************
v 1
<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <div id="dropzone-attachments" class="dropzone bg-gray-700 border border-gray-600 rounded-xl">
        <div class="dz-message text-center py-12">
            <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm text-gray-400">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
Dropzone.autoDiscover = false;

new Dropzone("#dropzone-attachments", {
    url: "{{ route('leader.tasks.store') }}", // Même route que ton form (ou une route dédiée si tu veux)
    method: "post",
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    paramName: "attachments[]", // Nom du champ
    maxFilesize: 10, // MB
    acceptedFiles: ".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4",
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "", // On gère le message avec HTML
    clickable: true,
    init: function() {
        this.on("addedfile", function(file) {
            // Icône personnalisée si pas image
            if (!file.type.match(/image.*/)) {
                this.emit("thumbnail", file, "/icons/file.png"); // Optionnel : icône générique
            }
        });
    }
});
</script>
@endpush
-----------------------------------------------------
v2 + 2 js v

<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <div id="attachments-dropzone" class="dropzone rounded-xl border-2 border-dashed border-gray-600 bg-gray-700">
        <div class="dz-message text-center py-12">
            <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>
----------------------------********
js v1 #attachments-dropzone

Validation dans controller (garde ça)

'attachments' => 'nullable|array',
'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,txt,zip,mp4|max:10240',

@push('scripts')
<script>
Dropzone.autoDiscover = false;

new Dropzone("#attachments-dropzone", {
    url: "{{ route('leader.tasks.store') }}", // Même route que ton form
    method: "post",
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    paramName: "attachments", // Laravel reçoit $request->file('attachments')
    uploadMultiple: true,
    parallelUploads: 5,
    maxFilesize: 10, // MB
    acceptedFiles: ".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4",
    addRemoveLinks: true,
    dictRemoveFile: "Remove",
    dictCancelUpload: "Cancel",
    dictDefaultMessage: "", // Message géré par HTML
    clickable: true,
    autoProcessQueue: false, // IMPORTANT : On upload seulement au submit du form principal
    previewTemplate: `
        <div class="dz-preview dz-file-preview">
            <div class="dz-image"><img data-dz-thumbnail /></div>
            <div class="dz-details">
                <div class="dz-filename"><span data-dz-name></span></div>
                <div class="dz-size" data-dz-size></div>
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            <div class="dz-success-mark"><i class="fas fa-check"></i></div>
            <div class="dz-error-mark"><i class="fas fa-times"></i></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
            <a class="dz-remove" href="javascript:undefined;" data-dz-remove>Remove</a>
        </div>
    `,
    init: function() {
        let myDropzone = this;

        // Quand on clique sur le bouton "Create Task" du form principal
        document.querySelector('#create-task-form button[type="submit"]').addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();

            if (myDropzone.getQueuedFiles().length > 0) {
                myDropzone.processQueue(); // Upload les fichiers
            } else {
                document.getElementById('create-task-form').submit(); // Submit normal si pas de fichiers
            }
        });

        // Après upload réussi, submit le form principal
        this.on("successmultiple", function(files, response) {
            document.getElementById('create-task-form').submit();
        });

        // Gestion erreurs
        this.on("errormultiple", function(files, response) {
            alert('Error uploading files: ' + response);
        });
    }
});
</script>
@endpush
------------*************

js v2 #attachments-dropzone

@push('scripts')
<script>
// Désactive la découverte automatique (car on a plusieurs dropzones possibles)
Dropzone.autoDiscover = false;

new Dropzone("#attachments-dropzone", {
    url: "{{ route('leader.tasks.store') }}", // Même route que ton form
    method: "post",
    headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    },
    paramName: "attachments[]", // Nom du champ Laravel
    maxFilesize: 10, // MB
    acceptedFiles: ".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4",
    addRemoveLinks: true,
    dictRemoveFile: "<i class='fas fa-trash text-danger'></i>",
    dictCancelUpload: "<i class='fas fa-times'></i>",
    clickable: true,
    init: function() {
        this.on("success", function(file, response) {
            console.log("Uploaded:", file.name);
        });
        this.on("error", function(file, error) {
            console.error("Error:", error);
        });
    }
});
</script>
@endpush
---------------------------------------------------------------------------------------
v3  + Script Dropzone

<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Input file normal (Laravel le reçoit parfaitement) -->
    <input type="file"
           name="attachments[]"
           id="attachments-input"
           multiple
           class="hidden"
           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

    <!-- Zone Dropzone (visuelle seulement) -->
    <div id="attachments-dropzone" class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all duration-300 bg-gray-700/50">
        <div class="text-gray-400">
            <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
// Important : désactive l'auto découverte
Dropzone.autoDiscover = false;

// Initialise Dropzone en mode "preview only" (pas d'upload automatique)
new Dropzone("#attachments-dropzone", {
    url: "#", // URL bidon (on n'envoie pas via Dropzone)
    autoProcessQueue: false, // Pas d'upload Ajax
    clickable: true,
    previewsContainer: false, // Pas de previews Dropzone (on gère nous-mêmes)
    maxFilesize: 10,
    acceptedFiles: ".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4",
    dictDefaultMessage: "", // Message géré par HTML
    init: function() {
        // Quand un fichier est ajouté (drag ou clic)
        this.on("addedfile", function(file) {
            // Vérification taille
            if (file.size > 10485760) {
                alert(`"${file.name}" dépasse 10MB`);
                this.removeFile(file);
                return;
            }

            // On ajoute le fichier à l'input caché (pour l'envoi normal du form)
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('attachments-input').files = dt.files;

            // Ici tu peux ajouter un aperçu personnalisé si tu veux
            console.log("Fichier ajouté :", file.name);
        });
    }
});
</script>
@endpush

-----------------------------------------------------
v4  + Vanilla.js

<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Input file normal (obligatoire pour Laravel) -->
    <input type="file"
           name="attachments[]"
           id="attachments-input"
           multiple
           class="hidden"
           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

    <!-- Zone drag & drop (visuelle) -->
    <div id="drop-zone"
         class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all duration-300 bg-gray-700/50">
        <div class="text-gray-400">
            <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    <!-- Aperçu des fichiers -->
    <div id="file-preview" class="mt-8 space-y-4"></div>

    <!-- Aucun fichier -->
    <div id="no-files" class="text-center py-10 text-gray-500 mt-6">
        <i class="fas fa-paperclip text-5xl mb-4 opacity-40"></i>
        <p class="text-xl">No attachments yet</p>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('attachments-input');
const filePreview = document.getElementById('file-preview');
const noFiles = document.getElementById('no-files');

// Clic sur la zone → ouvre le sélecteur
dropZone.addEventListener('click', () => fileInput.click());

// Effet drag
dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-primary/20');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-primary', 'bg-primary/20');
});

dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-primary/20');
    fileInput.files = e.dataTransfer.files;
    showPreview(e.dataTransfer.files);
});

// Quand on sélectionne via le sélecteur
fileInput.addEventListener('change', () => {
    showPreview(fileInput.files);
});

function showPreview(files) {
    filePreview.innerHTML = '';
    noFiles.style.display = files.length > 0 ? 'none' : 'block';

    Array.from(files).forEach(file => {
        if (file.size > 10485760) {
            alert(`"${file.name}" dépasse 10MB`);
            return;
        }

        let icon = 'fas fa-file text-gray-300';
        if (file.type.startsWith('image/')) icon = 'fas fa-file-image text-cyan-400';
        else if (file.type === 'application/pdf') icon = 'fas fa-file-pdf text-red-400';
        else if (file.type.includes('word')) icon = 'fas fa-file-word text-blue-400';
        else if (file.type.includes('excel')) icon = 'fas fa-file-excel text-green-400';
        else if (file.type === 'text/plain') icon = 'fas fa-file-alt text-gray-300';
        else if (file.type === 'application/zip') icon = 'fas fa-file-archive text-yellow-400';
        else if (file.type.startsWith('video/')) icon = 'fas fa-file-video text-purple-400';

        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-sm';
        div.innerHTML = `
            <div class="flex items-center gap-5">
                <i class="${icon} text-3xl"></i>
                <div>
                    <p class="font-semibold text-lg">${file.name}</p>
                    <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            </div>
        `;
        filePreview.appendChild(div);
    });
}
</script>
@endpush

---------------------------------------------------------------------------------------
v5 + Vanilla js
<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Input file normal (Laravel le reçoit parfaitement) -->
    <input type="file"
           name="attachments[]"
           id="attachments-input"
           multiple
           class="hidden"
           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

    <!-- Zone drag & drop visuelle -->
    <div id="drop-zone"
         class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all duration-300 bg-gray-700/50">
        <div id="drop-text" class="text-gray-400">
            <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    <!-- Aperçu des fichiers -->
    <div id="file-preview" class="mt-8 space-y-4"></div>

    <!-- Aucun fichier -->
    <div id="no-files" class="text-center py-10 text-gray-500 mt-6">
        <i class="fas fa-paperclip text-5xl mb-4 opacity-40"></i>
        <p class="text-xl">No attachments yet</p>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('attachments-input');
const filePreview = document.getElementById('file-preview');
const noFiles = document.getElementById('no-files');
const dropText = document.getElementById('drop-text');

// Clic → ouvre le sélecteur
dropZone.addEventListener('click', () => fileInput.click());

// Effet drag
dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    dropZone.classList.add('border-primary', 'bg-primary/20');
    dropText.classList.replace('text-gray-400', 'text-primary');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-primary', 'bg-primary/20');
    dropText.classList.replace('text-primary', 'text-gray-400');
});

// Drag & drop → assigne directement sans DataTransfer
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-primary', 'bg-primary/20');
    dropText.classList.replace('text-primary', 'text-gray-400');
    fileInput.files = e.dataTransfer.files;
    showPreview(e.dataTransfer.files);
});

// Quand on sélectionne
fileInput.addEventListener('change', () => showPreview(fileInput.files));

function showPreview(files) {
    filePreview.innerHTML = '';
    noFiles.style.display = files.length > 0 ? 'none' : 'block';

    Array.from(files).forEach(file => {
        if (file.size > 10485760) {
            alert(`"${file.name}" dépasse 10MB`);
            return;
        }

        let icon = 'fas fa-file text-gray-300';
        if (file.type.startsWith('image/')) icon = 'fas fa-file-image text-cyan-400';
        else if (file.type === 'application/pdf') icon = 'fas fa-file-pdf text-red-400';
        else if (file.type.includes('word')) icon = 'fas fa-file-word text-blue-400';
        else if (file.type.includes('excel')) icon = 'fas fa-file-excel text-green-400';
        else if (file.type === 'text/plain') icon = 'fas fa-file-alt text-gray-300';
        else if (file.type === 'application/zip') icon = 'fas fa-file-archive text-yellow-400';
        else if (file.type.startsWith('video/')) icon = 'fas fa-file-video text-purple-400';

        const div = document.createElement('div');
        div.className = 'flex items-center justify-between bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-sm';
        div.innerHTML = `
            <div class="flex items-center gap-5">
                <i class="${icon} text-3xl"></i>
                <div>
                    <p class="font-semibold text-lg">${file.name}</p>
                    <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                </div>
            </div>
        `;
        filePreview.appendChild(div);
    });
}
</script>
@endpush


---------------------------------------------------------------------------------------
v6
utiliser rahulhaque/laravel-filepond, un package Laravel dédié pour FilePond (drag & drop moderne, preview, suppression, validation parfaite, compatible Laravel 12).
C’est la plus propre et sans bug en 2026.

Installation (5 minutes)

Installe le package :

Bashcomposer require rahulhaque/laravel-filepond:^12.0

Publie la config et migration :

Bashphp artisan vendor:publish --provider="RahulHaque\Filepond\FilepondServiceProvider"
php artisan migrate

Ajoute FilePond CSS/JS dans ton layout appW.blade.php (dans <head> et avant </body>)

blade<!-- Dans <head> -->
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">

<!-- Avant </body> -->
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>



v1
<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Input normal que FilePond va transformer -->
    <input type="file"
           class="filepond"
           name="attachments[]"
           multiple
           data-max-file-size="10MB"
           data-accepted-file-types="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/zip,video/mp4">

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
// Parse tous les inputs avec class "filepond"
FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview // Optionnel pour preview images
);

// Optionnel : charge les plugins si tu veux preview images
// Ajoute ces CDN avant FilePond si tu veux preview
// <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
// <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
// <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>

const input = document.querySelector('.filepond');
const pond = FilePond.create(input, {
    allowRevert: true,
    allowRemove: true,
    server: {
        url: '{{ url('') }}', // URL de base
        process: '/filepond/process',
        revert: '/filepond/revert',
        // Les autres endpoints sont gérés automatiquement par le package
    },
    credits: false // Enlève le lien FilePond
});
</script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
@endpush


use RahulHaque\Filepond\Facades\Filepond;

// Dans store()
if ($request->has('attachments')) {
    $fileInfos = Filepond::field('attachments')->moveTo('tasks/' . $task->id);

    foreach ($fileInfos as $info) {
        $task->attachments()->create([
            'filename'    => $info['filename'],
            'path'        => $info['location'],
            'mime_type'   => $info['mimetype'],
            'size'        => $info['size'],
            'uploaded_by' => Auth::id(),
        ]);
    }
}


-----------------------------------------------------

v2 #



<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Input file normal que FilePond va transformer -->
    <input type="file"
           class="filepond"
           name="attachments[]"
           multiple>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>


@push('scripts')
<script>
// Enregistre les plugins optionnels (preview images, validation taille/type)
FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

// Charge les plugins via CDN (obligatoire pour preview + validation)
const head = document.getElementsByTagName('head')[0];
const scripts = [
    'https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js',
    'https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js',
    'https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js'
];

scripts.forEach(src => {
    const script = document.createElement('script');
    script.src = src;
    head.appendChild(script);
});

// Initialise FilePond
FilePond.setOptions({
    server: {
        url: '{{ url('') }}',
        process: '/filepond/process',
        revert: '/filepond/revert',
        restore: '/filepond/restore',
        load: '/filepond/load',
        fetch: '/filepond/fetch'
    },
    allowRevert: true,
    allowRemove: true,
    maxFileSize: '10MB',
    acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain', 'application/zip', 'video/mp4'],
    credits: false
});

const inputElement = document.querySelector('input.filepond');
const pond = FilePond.create(inputElement);
</script>
@endpush

use RahulHaque\Filepond\Facades\Filepond;

// Après $task = $project->tasks()->create($validated);

if ($request->has('attachments')) {
    $files = Filepond::field('attachments[]')->getFiles();

    foreach ($files as $file) {
        $moved = $file->moveTo('tasks/' . $task->id);

        $task->attachments()->create([
            'filename'    => $moved['filename'],
            'path'        => $moved['location'],
            'mime_type'   => $moved['mimetype'],
            'size'        => $moved['size'],
            'uploaded_by' => Auth::id(),
        ]);
    }
}


--------------------------------------------------------
v3 par chat #*


