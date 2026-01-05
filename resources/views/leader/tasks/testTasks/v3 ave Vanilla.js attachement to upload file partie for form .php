v 3
1.
<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Zone Drag & Drop + Clic -->
    <div id="drop-zone"
         class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all duration-300 bg-gray-700/50"
         onclick="document.getElementById('file-input').click();">

        <input type="file"
               id="file-input"
               name="attachments[]"
               multiple
               class="hidden"
               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

        <div class="text-gray-400">
            <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
            <p class="text-lg font-bold mb-3">Drag & drop files here</p>
            <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
            <p class="text-xs mt-3 text-gray-500">
                Max 10MB per file • Images, PDF, Office, ZIP, video...
            </p>
        </div>
    </div>

    <!-- Liste des fichiers sélectionnés -->
    <div id="file-list" class="mt-8 space-y-4"></div>

    <!-- Message quand aucun fichier -->
    <div id="no-files" class="text-center py-10 text-gray-500 mt-6">
        <i class="fas fa-paperclip text-5xl mb-4 opacity-40"></i>
        <p class="text-xl">No attachments yet</p>
    </div>

    @error('attachments.*')
        <div class="text-danger small mt-4">{{ $message }}</div>
    @enderror
</div>
----------------------------------------------------
2. + js
                       <!-- Attachments Section -->
                        <!-- Attachments classique marche   -->
                            {{--  <div class="bg-gray-700 border border-gray-600 rounded p-3">
                                <input type="file" name="attachments[]" multiple
                                    class="form-control bg-gray-700 text-white border-gray-500"
                                    accept=".jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">
                                <small class="text-gray-400 d-block mt-2">
                                    Multiple files allowed • Max 10MB per file • Formats: images, PDF, Office, ZIP, video
                                 </small>
                            </div> --}}
                             <!-- Attachments with ajax.js + drap & drop || x-data="attachments()" -->
                        <!-- Attachments Section -->
                        <h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

                        <div class="mb-5">
                            <!-- Zone Drag & Drop + Clic -->
                            <div id="drop-zone"
                                class="border-2 border-dashed border-gray-600 rounded-xl p-12 text-center cursor-pointer hover:border-primary transition-all duration-300 bg-gray-700/50"
                                onclick="document.getElementById('file-input').click();">

                                <input type="file"
                                    id="file-input"
                                    name="attachments[]"
                                    multiple
                                    class="hidden"
                                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

                                <div class="text-gray-400">
                                    <i class="fas fa-cloud-upload-alt text-8xl mb-6 opacity-70"></i>
                                    <p class="text-lg font-bold mb-3">Drag & drop files here</p>
                                    <p class="text-sm">or <span class="text-primary underline">click to browse</span></p>
                                    <p class="text-xs mt-3 text-gray-500">
                                        Max 10MB per file • Images, PDF, Office, ZIP, video...
                                    </p>
                                </div>
                            </div>

                            <!-- Liste des fichiers sélectionnés -->
                            <div id="file-list" class="mt-8 space-y-4"></div>

                            <!-- Message quand aucun fichier -->
                            <div id="no-files" class="text-center py-10 text-gray-500 mt-6">
                                <i class="fas fa-paperclip text-5xl mb-4 opacity-40"></i>
                                <p class="text-xl">No attachments yet</p>
                            </div>

                            @error('attachments.*')
                                <div class="text-danger small mt-4">{{ $message }}</div>
                            @enderror
                        </div>

---------------------------------------------------------------------------------
@push('scripts')

    <!--Scriipt v2 :: Alpine.js pour gérer les uploads en Ajax :: Version finale avec attachments + Ajax -->
    <!--// Vanilla JS pour drag & drop + sélection script v3 -->
    <script>
        // Gestion Drag & Drop + Sélection de fichiers
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const noFilesMsg = document.getElementById('no-files');

        // Effet visuel au survol drag
        dropZone.addEventListener('dragover', e => {
            e.preventDefault();
            dropZone.classList.add('border-primary', 'bg-primary/20', 'shadow-2xl', 'shadow-primary/30');
            dropZone.querySelector('div').classList.replace('text-gray-400', 'text-primary');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-primary', 'bg-primary/20', 'shadow-2xl', 'shadow-primary/30');
            dropZone.querySelector('div').classList.replace('text-primary', 'text-gray-400');
        });

        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/20', 'shadow-2xl', 'shadow-primary/30');
            dropZone.querySelector('div').classList.replace('text-primary', 'text-gray-400');
            handleFiles(e.dataTransfer.files);
        });

        // Gestion des fichiers sélectionnés (clic ou drop)
        function handleFiles(files) {
            const validFiles = [];

            Array.from(files).forEach(file => {
                if (file.size > 10485760) { // 10MB
                    alert(`"${file.name}" exceeds 10MB limit`);
                    return;
                }
                validFiles.push(file);
            });

            // Met à jour l'input file pour l'envoi du form
            const dt = new DataTransfer();
            validFiles.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;

            // Affiche la liste
            displayFileList(validFiles);
        }

        // Affiche la liste des fichiers
        function displayFileList(files) {


            fileList.innerHTML = '';
            noFilesMsg.style.display = files.length > 0 ? 'none' : 'block';

            files.forEach(file => {
                // Icône selon type
                let icon = 'fas fa-file';
                if (file.type.startsWith('image/')) icon = 'fas fa-file-image text-cyan-400';
                else if (file.type === 'application/pdf') icon = 'fas fa-file-pdf text-red-400';
                else if (file.type.includes('word')) icon = 'fas fa-file-word text-blue-400';
                else if (file.type.includes('excel') || file.type.includes('spreadsheet')) icon = 'fas fa-file-excel text-green-400';
                else if (file.type === 'text/plain') icon = 'fas fa-file-alt text-gray-300';
                else if (file.type === 'application/zip') icon = 'fas fa-file-archive text-yellow-400';
                else if (file.type.startsWith('video/')) icon = 'fas fa-file-video text-purple-400';

                const div = document.createElement('div');

                div.className = 'flex items-center justify-between bg-gray-800 p-5 rounded-xl border border-gray-700 hover:bg-gray-750 transition';
                div.innerHTML = `
                    <div class="flex items-center gap-5">
                        <i class="${icon} text-primary text-3xl"></i>
                        <div>
                            <p class="font-medium text-lg">${file.name}</p>
                            <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile('${file.name.replace(/'/g, "\\'")}')"
                            class="text-danger hover:text-red-400 p-3 rounded-lg bg-gray-700 hover:bg-gray-600 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                `;
                fileList.appendChild(div);
            });
        }

        // Supprime un fichier
        function removeFile(fileName) {
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach(file => {
                if (file.name !== fileName) dt.items.add(file);
            });
            fileInput.files = dt.files;
            displayFileList(Array.from(fileInput.files));
        }
    </script>
@endpush


----------------------------------------------------
v4 + js


 <!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Zone Drag & Drop -->
    <label class="block">
        <input type="file"
               name="attachments[]"
               id="attachments-input"
               multiple
               class="hidden"
               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

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
    </label>

    <!-- Liste des fichiers -->
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


 <script>
        // Gestion Drag & Drop + Sélection de fichiers

const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('attachments-input');
const filePreview = document.getElementById('file-preview');
const noFiles = document.getElementById('no-files');

// Ouvrir le sélecteur au clic sur la zone
dropZone.addEventListener('click', () => fileInput.click());

// Effet drag & drop
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

// Quand on sélectionne via le vrai input
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




---------------------------------------------------------------------------------
v5 + js %  (Vanilla JS)


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

-----------------------------------------

<!-- Attachments Section -->
<h5 class="fw-bold mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments</h5>

<div class="mb-5">
    <!-- Zone Drag & Drop -->
    <label class="block">
        <input type="file"
               name="attachments[]"
               id="attachments-input"
               multiple
               class="hidden"
               accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.mp4">

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
    </label>

    <!-- Liste des fichiers -->
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

// Ouvrir le sélecteur au clic sur la zone
dropZone.addEventListener('click', () => fileInput.click());

// Effet drag & drop
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

// Quand on sélectionne via le vrai input
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
