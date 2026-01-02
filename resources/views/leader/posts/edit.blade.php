@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="card bg-gray-800 text-white shadow-2xl border-0 rounded-xl">
                    <div class="card-header bg-gradient-warning text-center py-4">
                        <h3 class="fw-bold mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Post
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('leader.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('Put') <!-- Très important ! -->

                            <!-- Team Selection -->
                            @if($teams->count() > 1)
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Post in team</label>
                                    <select name="team_id" class="form-select bg-gray-700 border-gray-600 text-white" required>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ old('team_id', $post->team_id) == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="team_id" value="{{ $teams->first()->id }}">
                                <div class="alert alert-info mb-4">
                                    Posting in: <strong>{{ $teams->first()->name }}</strong>
                                </div>
                            @endif

                            <!-- Title -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Title (optional)</label>
                                <input type="text" name="title" class="form-control bg-gray-700 border-gray-600 text-white"
                                       value="{{ old('title', $post->title) }}">
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="content" rows="8" class="form-control bg-gray-700 border-gray-600 text-white" required>{{ old('content', $post->content) }}</textarea>
                            </div>

                            <!-- Current Image -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Current image</label>
                                @if($post->image)
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/' . $post->image) }}"
                                             class="img-fluid rounded shadow"
                                             style="max-height: 300px;">
                                        <div class="mt-3">
                                            <button type="submit" name="action" value="delete_image" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i> Delete image only
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500">No image attached</p>
                                @endif

                                <!-- New Image -->
                                <label class="form-label fw-semibold mt-4">Replace image (optional)</label>
                                <input type="file" name="image" class="form-control bg-gray-700 border-gray-600 text-white" accept="image/*">
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('leader.posts.show', $post) }}" class="btn btn-outline-light">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg px-5">
                                    <i class="fas fa-save me-2"></i> Update Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
