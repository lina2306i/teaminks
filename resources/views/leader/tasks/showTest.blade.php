{{-- resources/views/tasks/show.blade.php --}}

@extends('layouts.appW')

@section('contentW')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                {{-- Informations de la tâche --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>{{ $task->title }}
                        </h3>
                        <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                    </div>

                    <div class="card-body">

                        {{-- ... Autres sections ... --}}
                    </div>
                </div>

                {{-- ✅ SECTION FICHIERS AVEC BOUTON EDIT --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-paperclip me-2"></i>
                            Pièces jointes
                            <span class="badge bg-light text-dark">{{ $task->attachments->count() }}</span>
                        </h5>

                        {{-- ✅ BOUTON EDIT FILES --}}
                        <button type="button"
                                class="btn btn-light btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editFilesModal">
                            <i class="fas fa-edit me-1"></i>Gérer les fichiers
                        </button>
                    </div>

                    <div class="card-body">
                        @if($task->attachments->count() > 0)
                            <div class="list-group">
                                @foreach($task->attachments as $attachment)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ getFileIconHelper($attachment->mime_type) }} fa-2x text-primary me-3"></i>
                                        <div>
                                            <a href="{{ asset('storage/' . $attachment->path) }}"
                                            target="_blank"
                                            class="fw-bold text-decoration-none">
                                                {{ $attachment->filename }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {{ formatBytesHelper($attachment->size) }} •
                                                Ajouté le {{ $attachment->created_at->format('d/m/Y à H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $attachment->path) }}"
                                    download
                                    class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>Aucun fichier attaché</p>
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editFilesModal">
                                    <i class="fas fa-plus me-1"></i>Ajouter des fichiers
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar avec actions --}}
            <div class="col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('leader.tasks.edit', $task) }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Modifier la tâche
                        </a>
                        <button type="button"
                                class="btn btn-secondary w-100 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editFilesModal">
                            <i class="fas fa-paperclip me-2"></i>Gérer les fichiers
                        </button>
                        <form action="{{ route('leader.tasks.destroy', $task) }}" method="POST" class="d-inline w-100">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-danger w-100"
                                    onclick="return confirm('Supprimer cette tâche ?')">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <i class="fas fa-paperclip me-2"></i>Gérer les fichiers
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- ✅ INCLUSION DU COMPOSANT --}}
                        @include('tasks.editFile', ['task' => $task])
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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
