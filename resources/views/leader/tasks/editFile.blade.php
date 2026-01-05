{{-- resources/views/tasks/editFile.blade.php --}}
{{-- Composant réutilisable pour la gestion des fichiers --}}
{{-- Section Upload de fichiers dans edit.blade.php --}}
<div class="mb-5">
    <h5 class="fw-bold mb-4 text-primary"> <i class="fas fa-paperclip me-2"></i>Attachments — Edit File</h5>
    @php
        // Fonctions helper pour la vue
        function getFileIcon($mimeType) {
            if (str_starts_with($mimeType, 'image/')) return 'fas fa-image';
            if (str_contains($mimeType, 'pdf')) return 'fas fa-file-pdf';
            if (str_contains($mimeType, 'word')) return 'fas fa-file-word';
            if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) return 'fas fa-file-excel';
            if (str_contains($mimeType, 'zip')) return 'fas fa-file-archive';
            if (str_contains($mimeType, 'video')) return 'fas fa-file-video';
            return 'fas fa-file';
        }

        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        }
    @endphp
    {{-- Liste des fichiers existants --}}
    @if($task->attachments->count() > 0)
        <div class="mb-4">
            <p class="text-sm text-gray-400 text-lg font-bold d-block mb-2">
                Current Files :
                <span class="badge bg-primary">{{ $task->attachments->count() }}/5</span>
            </p>
            <div id="existingFilesList">
                @foreach($task->attachments as $attachment)
                    <div class="file-item existing-file" data-attachment-id="{{ $attachment->id }}">
                        <div class="file-icon">
                            <i class="{{ getFileIcon($attachment->mime_type) }}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">
                                <a href="{{ asset('storage/' . $attachment->path) }}"
                                target="_blank"
                                class="text-white text-decoration-none">
                                    {{ $attachment->filename }}
                                </a>
                            </div>
                            <div class="file-size">
                                {{ formatBytes($attachment->size) }} •
                                🔺 Added on  {{ $attachment->created_at->format('H:i - d/m/Y') }}
                                <small class="text-gray-700 d-block">
                                    {{-- number_format($attachment->size / 1024, 1) KB --}}
                                    • Uploaded {{ $attachment->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <button type="button"
                                class="file-remove btn-delete-attachment"
                                data-attachment-id="{{ $attachment->id }}"
                                title="Supprimer ce fichier">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Zone d'upload pour nouveaux fichiers --}}
    @if($task->attachments->count() < 5)
        <div id="dropZone" class="bg-gray-700 border border-2 border-dashed border-gray-600 rounded-xl p-5 text-center position-relative"
            style="min-height: 200px; cursor: pointer; transition: all 0.3s;">
            {{-- Input --}}
            <label for="upload" class="form-label fw-semibold"><i class="fas fa-paperclip me-2"></i>Upload files</label>
            <input type="file" id="fileInput"  name="attachments[]"  multiple
                accept="*/*"   class="d-none"   max="{{ 5 - $task->attachments->count() }}">

            <div id="dropZoneContent" class="py-4">
                <i class="fas fa-cloud-upload-alt fa-4x text-gray-400 mb-3"></i>
                <p class="text-lg font-bold mb-2">Add New File Here</p>
                <p class="text-sm text-gray-400">Drag drop files here or  click to browse </p>
                <p class="text-xs mt-3 text-gray-500">
                    {{ 5 - $task->attachments->count() }} File(s) left • Max 10MB per file
                </p>
            </div>
        </div>
        {{-- Liste des nouveaux fichiers à uploader --}}
        <div id="newFilesList" class="mt-3"></div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            You have reached the limit of 5 files. Delete one to add a new.        </div>
    @endif

    <small class="text-gray-400 d-block mt-2">
        <i class="fas fa-cloud-upload-alt text-8xl text-gray-400 mb-6"></i>
        Max 5 files 🔹 Max 10MB per file 🔹
        <i class="fas fa-info-circle me-1"></i>
        Multiple files allowed •• Supported : Images, PDF, Office, ZIP, Video...
    </small>

    @error('attachments.*')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
</div>

{{-- Inputs cachés pour les fichiers à supprimer --}}
<div id="deleteAttachmentsContainer"></div>

@once

    @push('styles')
        <style>
            #dropZone.drag-over {
                background-color: rgba(59, 130, 246, 0.1);
                border-color: #3b82f6 !important;
                transform: scale(1.02);
            }

            .file-item {
                display: flex;
                align-items: center;
                padding: 12px 16px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 10px;
                margin-bottom: 10px;
                transition: all 0.3s;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            .file-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            }

            .file-item.existing-file {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }

            .file-item.pending-delete {
                opacity: 0.5;
                background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
                position: relative;
            }

            .file-item.pending-delete::after {
                content: "Sera supprimé";
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(220, 53, 69, 0.9);
                color: white;
                padding: 5px 15px;
                border-radius: 5px;
                font-weight: bold;
                font-size: 0.85rem;
            }

            .file-icon {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                /*background-color: rgba(255, 255, 255, 0.2); color: white;*/
                background-color: rgba(238, 210, 210, 0.656);
                color: rgba(123, 131, 240, 0.792);
                border-radius: 8px;
                margin-right: 15px;
                font-size: 1.2rem;
            }

            .file-info {
                flex-grow: 1;
                color:  rgb(247, 246, 245);
            }

            .file-name {
                font-weight: 600;
                margin-bottom: 3px;
                font-size: 0.95rem;
            }

            .file-name a {
                color: white !important;
            }

            .file-name a:hover {
                text-decoration: underline !important;
            }

            .file-size {
                font-size: 0.8rem;
                opacity: 0.8;
            }

            .file-remove {
                cursor: pointer;
                color: rgb(251, 37, 37);
                font-size: 1.3rem;
                padding: 5px 10px;
                transition: all 0.3s;
                opacity: 0.7;
                background: none;
                border: none;
            }

            .file-remove:hover {
                opacity: 1;
                transform: scale(1.2);
            }

            .bg-gray-700 {
                background-color: #374151;
            }

            .border-gray-600 {
                border-color: #4b5563;
            }

            .text-gray-400 {
                color: #9ca3af;
            }

            .text-gray-500 {
                color: #6b7280;
            }

            .text-primary {
                color: #3b82f6;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('fileInput');
            const newFilesList = document.getElementById('newFilesList');
            const deleteContainer = document.getElementById('deleteAttachmentsContainer');
            const maxFiles = {{ 5 - $task->attachments->count() }};
            let selectedFiles = [];
            let filesToDelete = [];

            // Gestion de la suppression des fichiers existants
            document.querySelectorAll('.btn-delete-attachment').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const attachmentId = this.getAttribute('data-attachment-id');
                    const fileItem = this.closest('.file-item');

                    if (confirm('Are you sure you want to delete this file?')) {
                        // Marquer visuellement comme à supprimer
                        fileItem.classList.add('pending-delete');

                        // Ajouter à la liste des fichiers à supprimer
                        if (!filesToDelete.includes(attachmentId)) {
                            filesToDelete.push(attachmentId);

                            // Créer un input caché pour envoyer l'ID à supprimer
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'delete_attachments[]';
                            input.value = attachmentId;
                            input.id = 'delete-' + attachmentId;
                            deleteContainer.appendChild(input);
                        }

                        // Désactiver le bouton de suppression
                        this.disabled = true;
                        this.style.opacity = '0.3';
                    }
                });
            });

            // Gestion de l'upload de nouveaux fichiers
            if (dropZone) {
                dropZone.addEventListener('click', function(e) {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function(e) {
                    handleFiles(e.target.files);
                });

                dropZone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('drag-over');
                });

                dropZone.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('drag-over');
                });

                dropZone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('drag-over');
                    handleFiles(e.dataTransfer.files);
                });
            }

            function handleFiles(files) {
                const filesArray = Array.from(files);

                if (selectedFiles.length + filesArray.length > maxFiles) {
                    alert(`You can only add ${maxFiles} more file(s) as supplementery).`);
                    return;
                }

                filesArray.forEach(file => {
                    if (file.size > 10 * 1024 * 1024) {
                        alert(`The file ${file.name} is too large (max 10MB).`);
                        return;
                    }
                    selectedFiles.push(file);
                });

                updateNewFilesList();
                updateFileInput();
            }

            function updateNewFilesList() {
                newFilesList.innerHTML = '';

                if (selectedFiles.length === 0) return;

                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';

                    const icon = getFileIcon(file.type);

                    fileItem.innerHTML = `
                        <div class="file-icon">
                            <i class="${icon}"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name">${escapeHtml(file.name)}</div>
                            <div class="file-size">${formatFileSize(file.size)} • Nouveau</div>
                        </div>
                        <button type="button" class="file-remove" data-index="${index}">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    `;

                    newFilesList.appendChild(fileItem);
                });

                // document.querySelectorAll('.file-remove[data-index]').forEach(btn => {
                document.querySelectorAll('.file-remove').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const index = parseInt(this.getAttribute('data-index'));
                        removeNewFile(index);
                    });
                });
            }

            function removeNewFile(index) {
                selectedFiles.splice(index, 1);
                updateNewFilesList();
                updateFileInput();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            }

            function getFileIcon(mimeType) {
                if (mimeType.startsWith('image/')) return 'fas fa-image';
                if (mimeType.includes('pdf')) return 'fas fa-file-pdf';
                if (mimeType.includes('word')) return 'fas fa-file-word';
                if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fas fa-file-excel';
                if (mimeType.includes('zip')) return 'fas fa-file-archive';
                if (mimeType.includes('video')) return 'fas fa-file-video';
                return 'fas fa-file';
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }
        });
        </script>
    @endpush

@endonce
