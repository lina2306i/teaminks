{{-- unitil model  --}}
<!-- Modal Création Post -->
<div class="modal fade" id="createPostModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-gray-900 text-white border-gray-700">
            <form action="{{ route('leader.posts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-gray-700">
                    <h5 class="modal-title">Add Post Model</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-title" class="form-label text-lg">Title</label>
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <input type="text"
                               name="title"
                               id="create-title"
                               class="form-control bg-gray-800 border-gray-600 text-white mt-2"
                               placeholder="Post Title"
                               value="{{ old('title', $post->title ?? '') }}" placeholder="Ex: - Nouvelle fonctionnalité"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="create-content" class="form-label text-lg">Content</label>
                        @error('content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <textarea name="content"
                                  id="create-content"
                                  rows="5"
                                  class="form-control bg-gray-800 border-gray-600 text-white mt-2"
                                  placeholder="Write your post content..."
                                  required>{{ old('content') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-gray-700 justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Create
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('styles')
    <!-- Posts CSS -->
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
@endpush

{{-- -

<!-- Create Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-gray-900 text-white border-gray-700">
            <form action="{{ route('leader.posts.store') }}" method="POST">
                @csrf
                <div class="modal-header border-gray-700">
                    <h5 class="modal-title">Add Post</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="create-title" class="form-label text-lg">Title</label>
                        @error('title')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <input type="text"
                               name="title"
                               id="create-title"
                               class="form-control bg-gray-800 border-gray-600 text-white mt-2"
                               placeholder="Post Title"
                               value="{{ old('title', $post->title ?? '') }}" placeholder="Ex: - New feature"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="create-content" class="form-label text-lg">Content</label>
                        @error('content')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        <textarea name="content"
                                  id="create-content"
                                  rows="5"
                                  class="form-control bg-gray-800 border-gray-600 text-white mt-2"
                                  placeholder="Write your post content..."
                                  required>{{ old('content') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-gray-700 justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        Create
                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('styles')
    <!-- Posts CSS -->
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
@endpush

--}}
