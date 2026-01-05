<!-- Attachments not needed
 @ include('leader.tasks.partials._attachments', ['task' => $task]) -->


@php
    // Fonctions helper (si pas déjà définies ailleurs)
    if (!function_exists('getFileIconHelper')) {
        function getFileIconHelper($mimeType) {
            if (str_starts_with($mimeType, 'image/')) return 'fas fa-image';
            if (str_contains($mimeType, 'pdf')) return 'fas fa-file-pdf';
            if (str_contains($mimeType, 'word')) return 'fas fa-file-word';
            if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) return 'fas fa-file-excel';
            if (str_contains($mimeType, 'zip')) return 'fas fa-file-archive';
            if (str_contains($mimeType, 'video')) return 'fas fa-file-video';
            return 'fas fa-file';
        }
    }

    if (!function_exists('formatBytesHelper')) {
        function formatBytesHelper($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        }
    }
@endphp


 <!-- Attachement  @ if($task->attachments_count > 0) -->
                        <h3 class="h5 mb-4 text-primary"><i class="fas fa-paperclip me-2"></i>Attachments — (Max 5 files)</h3>
                        @if($task->attachments->count() > 0)
                            <div class="card bg-gray-700 text-white border-0 shadow mt-4">
                                <div class="card-header bg-secondary fw-bold d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-file-export me-2"></i> Attachment(s)
                                        <span class="badge bg-light text-dark">{{ $task->attachments->count() }}</span>
                                    </h5>
                                    {{-- ✅ BOUTON EDIT FILES --}}
                                    <button type="button"
                                            class="btn btn-light btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editFilesModal">
                                        <i class="fas fa-edit me-1"></i>Files Manager
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-wrap gap-3  row g-3">
                                        @foreach($task->attachments as $attachment)
                                            <div class="col-md-6 col-lg-4">
                                                <div class="bg-gray-700 rounded p-3 d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center overflow-hidden">
                                                        <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
                                                        <div class="text-truncate">
                                                            <a href="{{ Storage::url($attachment->path) }}"
                                                            target="_blank"
                                                            class="text-white hover:text-primary text-decoration-none fw-medium">
                                                                {{ $attachment->filename }}
                                                            </a>
                                                            <small class="text-gray-400 d-block">
                                                                {{ number_format($attachment->size / 1024, 1) }} KB
                                                                • Uploaded {{ $attachment->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>

                                                    <!-- Bouton télécharger -->
                                                    <a href="{{ Storage::url($attachment->path) }}"
                                                    download="{{ $attachment->filename }}"
                                                    class="btn btn-sm btn-outline-light ms-2"
                                                    title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5 text-gray-400">
                                <i class="fas fa-paperclip fa-3x mb-3 opacity-50"></i>
                                <p>No attachments yet.</p>
                            </div>
                        @endif
                        <br>
                        <h5 class="mt-6 font-semibold">
                            <i class="fas fa-solid fa-eye me-2"></i> Seen Attachments
                        </h5>
                        <ul class="mt-3 space-y-2">
                            @foreach($task->attachments as $file)
                                <li class="flex items-center gap-2">
                                    📎
                                    <a href="{{ Storage::url($file->path) }}" target="_blank"  class="text-blue-600 hover:underline">
                                        {{ $file->filename }}
                                    </a>
                                    <span class="text-xs text-gray-500">({{ $file->uploader->name ?? 'Unknown' }}) </span>
                                </li>
                            @endforeach
                        </ul>
                        <br>
                        <hr>

                        {{-- ✅ SECTION FICHIERS AVEC BOUTON EDIT --}}
                        <div class="card shadow mb-4">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-paperclip me-2"></i> Attachment(s)
                                    <span class="badge bg-light text-dark">{{ $task->attachments->count() }}</span>
                                </h5>

                                {{-- ✅ BOUTON EDIT FILES --}}
                                <button type="button"   class="btn btn-light btn-sm"   data-bs-toggle="modal"  data-bs-target="#editFilesModal">
                                     Files Manager <i class="fas fa-edit me-1"></i>
                                </button>
                            </div>

                            <div class="card-body">
                                @if($task->attachments->count() > 0)
                                    <div class="list-group">
                                        @foreach($task->attachments as $attachment)
                                        <div class="list-group-item  d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i class="{{ getFileIconHelper($attachment->mime_type) }} fa-2x text-primary me-3"></i>
                                                <div>
                                                    <a href="{{ asset('storage/' . $attachment->path) }}"
                                                    target="_blank"
                                                    class="fw-bold text-decoration-none">
                                                        {{ $attachment->filename }}
                                                    </a>
                                                   <span class="text-xs text-gray-400">({{ $file->uploader->name ?? 'Unknown' }}) </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Added on {{ $attachment->created_at->format('d/m/Y à H:i') }}
                                                        • Uploaded {{ $attachment->created_at->diffForHumans() }}
                                                    </small>
                                                    <small class="text-gray-400 ">
                                                        _ {{ formatBytesHelper($attachment->size) }}
                                                        {{-- number_format($attachment->size / 1024, 2) KB--}}
                                                    </small>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $attachment->path) }}"
                                                download="{{ $attachment->filename }}"
                                                class="btn btn-sm btn-outline-primary">
                                                     <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <p>No attachments yet.</p>
                                        <button type="button"
                                                class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editFilesModal">
                                            <i class="fas fa-plus me-1"></i>Add File(s)
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>


    {{-- ✅ MODAL POUR ÉDITER LES FICHIERS --}}
    <div class="modal fade" id="editFilesModal" tabindex="-1" aria-labelledby="editFilesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('leader.tasks.update', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Champs cachés pour garder les valeurs actuelles --}}
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="title" value="{{ $task->title }}">
                    <input type="hidden" name="description" value="{{ $task->description }}">
                    <input type="hidden" name="status" value="{{ $task->status }}">
                    <input type="hidden" name="difficulty" value="{{ $task->difficulty }}">
                    <input type="hidden" name="points" value="{{ $task->points }}">
                    <input type="hidden" name="priority" value="{{ $task->priority }}">
                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editFilesModalLabel">
                            <i class="fas fa-paperclip me-2"></i> Files Manager
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- ✅ INCLUSION DU COMPOSANT --}}
                        @include('leader.tasks.editFile', ['task' => $task])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save updates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const dropZone = document.getElementById('dropZone');
                const fileInput = document.getElementById('fileInput');
            // const browseBtn = document.getElementById('browseBtn'); // 1
                const filesList = document.getElementById('filesList');
                const maxFiles = 5;
                let selectedFiles = [];

                // Click sur le bouton parcourir
            /* browseBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    fileInput.click();
                });*/
                // 2
                // Click sur la zone pour ouvrir le sélecteur
                dropZone.addEventListener('click', function(e) {
                    fileInput.click();
                });

                // Click sur la zone de drop
                /*   dropZone.addEventListener('click', function(e) {
                    if (e.target.id !== 'browseBtn' && !e.target.closest('#browseBtn')) {
                        fileInput.click();  // 2 ss if
                    }
                });*/

                // Sélection de fichiers via l'input
                fileInput.addEventListener('change', function(e) {
                    handleFiles(e.target.files);
                });

                // Drag & Drop events
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

                    const files = e.dataTransfer.files;
                    handleFiles(files);
                    // 2 :handleFiles(e.dataTransfer.files);

                });

                function handleFiles(files) {
                    const filesArray = Array.from(files);

                    // Vérifier le nombre total de fichiers
                    if (selectedFiles.length + filesArray.length > maxFiles) {
                        alert(`You can only add ${maxFiles} more file(s).`);
                        return;
                    }

                    // Ajouter les nouveaux fichiers
                    filesArray.forEach(file => {
                        // Vérifier la taille du fichier (10MB max)
                        if (file.size > 10 * 1024 * 1024) {
                            alert(`The file ${file.name} is too large (max 10MB).`);
                            return;
                        }

                        selectedFiles.push(file);
                    });

                    updateFilesList();
                    updateFileInput();
                }

                function updateFilesList() {
                    filesList.innerHTML = '';

                    if (selectedFiles.length === 0) {
                        return;
                    }

                    selectedFiles.forEach((file, index) => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'file-item';

                        const icon = getFileIcon(file.type);  //2

                        fileItem.innerHTML = `
                            <div class="file-icon">
                                <!-- i class="bi bi-file-earmark"></ -->
                                <i class="${icon}"></i>
                            </div>
                            <div class="file-info">
                                <div class="file-name">${escapeHtml(file.name)}</div>
                                <div class="file-size">${formatFileSize(file.size)}</div>
                            </div>
                            <div class="file-remove" data-index="${index}">
                                <i class="bi bi-x-circle-fill"></i>
                                <i class="fas fa-times-circle"></i>
                            </div>
                        `;

                        filesList.appendChild(fileItem);
                    });

                    // Ajouter les événements de suppression
                    document.querySelectorAll('.file-remove').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            removeFile(index);
                        });
                    });
                }

                function removeFile(index) {
                    selectedFiles.splice(index, 1);
                    updateFilesList();
                    updateFileInput();
                }

                function updateFileInput() {
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });
                    fileInput.files = dataTransfer.files;
                }

                //2
                function getFileIcon(mimeType) {
                    if (mimeType.startsWith('image/')) return 'fas fa-image';
                    if (mimeType.includes('pdf')) return 'fas fa-file-pdf';
                    if (mimeType.includes('word')) return 'fas fa-file-word';
                    if (mimeType.includes('excel') || mimeType.includes('spreadsheet')) return 'fas fa-file-excel';
                    if (mimeType.includes('zip')) return 'fas fa-file-archive';
                    if (mimeType.includes('video')) return 'fas fa-file-video';
                    return 'fas fa-file';
                }
                // 2+1
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

            .file-icon {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: rgba(238, 210, 210, 0.656);
                color: rgba(123, 131, 240, 0.792);
                border-radius: 8px;
                margin-right: 15px;
                font-size: 1.2rem;
            }

            .file-info {
                flex-grow: 1;
                color: rgb(247, 246, 245);
            }

            .file-name {
                font-weight: 600;
                margin-bottom: 3px;
                font-size: 0.95rem;
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
            }

            .file-remove:hover {
                opacity: 1;
                transform: scale(1.2);
            }
            .border-gray-600 {
                border-color: #4b5563;
            }
        </style>
    @endpush

    @php
        // Fonctions helper (si pas déjà définies ailleurs)
        if (!function_exists('getFileIconHelper')) {
            function getFileIconHelper($mimeType) {
                if (str_starts_with($mimeType, 'image/')) return 'fas fa-image';
                if (str_contains($mimeType, 'pdf')) return 'fas fa-file-pdf';
                if (str_contains($mimeType, 'word')) return 'fas fa-file-word';
                if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) return 'fas fa-file-excel';
                if (str_contains($mimeType, 'zip')) return 'fas fa-file-archive';
                if (str_contains($mimeType, 'video')) return 'fas fa-file-video';
                return 'fas fa-file';
            }
        }

        if (!function_exists('formatBytesHelper')) {
            function formatBytesHelper($bytes, $precision = 2) {
                $units = ['B', 'KB', 'MB', 'GB'];
                $bytes = max($bytes, 0);
                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);
                $bytes /= pow(1024, $pow);
                return round($bytes, $precision) . ' ' . $units[$pow];
            }
        }
    @endphp

@endonce
