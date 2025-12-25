@extends('layouts.appW')

@section('contentW')
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Notes</h1>
            <button class="btn btn-lg btn-contact" data-bs-toggle="modal" data-bs-target="#newNoteModal">
                <i class="fas fa-plus me-2"></i> Nouvelle note
            </button>
        </div>

        <div class="row g-4">
            @foreach($notes as $note)
                <div class="col-md-6 col-lg-4">
                    <div class="card bg-gray-800 text-white border-0 shadow">
                        <div class="card-body">
                            <h5>{{ $note->title }}</h5>
                            <p class="text-gray-300 small">{{ Str::limit($note->content, 120) }}</p>
                            <small class="text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal pour créer une note -->
    <div class="modal fade" id="newNoteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-gray-900 text-white">
                <div class="modal-header border-gray-700">
                    <h5 class="modal-title">Nouvelle note</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('leader.notes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control form-control-lg" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contenu</label>
                            <textarea name="content" rows="8" class="form-control form-control-lg" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-gray-700">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-contact text-white">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
