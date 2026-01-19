@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100">
    <div class="container">
        <div class="max-w-4xl mx-auto">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('leader.posts.index') }}" class="btn btn-outline-light">
                    ← Back to posts
                </a>
            </div>

            <!-- Post Card -->
            <div class="card bg-gray-900 border border-gray-700 rounded-xl shadow-2xl text-white">
                <div class="card-body p-6">
                    <!-- Header: Author + Date
                        class="w-16 h-16 rounded-full border-4 border-blue-500 object-cover">
                    -->
                    <div class="flex items-center gap-4 mb-5">
                        <img src="{{ $post->user->profile ?? asset('images/user-default.jpg') }}"
                             alt="Profile"
                                class="rounded-circle me-3 shadow-sm" width="50" height="50">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user text-blue-400"></i>
                                <h5 class="fw-bold mb-0">{{ $post->user->name }}</h5>
                            </div>
                            <small class="text-gray-400 d-flex align-items-center gap-1 mt-1">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $post->created_at->diffForHumans() }}
                            </small>


                        </div>

                        <!-- Author + Date + Actions (Edit/Delete) -->
                        <div class="flex-1 d-flex align-items-center justify-content-between mb-4">
                            <!-- Edit / Delete Buttons (if author or leader) -->
                            @if(auth()->id() === $post->user_id || ($post->team && $post->team->leader_id === auth()->id()))
                                <div class="d-flex gap-2">
                                    <a href="{{ route('leader.posts.edit', $post) }}"
                                    class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-edit">  Edit</i>
                                    </a>
                                    <form action="{{ route('leader.posts.destroy', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete this post?')">
                                            <i class="fas fa-trash">  Delete</i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>

                    </div>

                    <!-- Title and Content -->
                    <h1 class="text-3xl fw-bold mb-3">{{ $post->title ?? 'Untitled Post' }}</h1>
                    <p class="text-gray-200 text-lg mb-5 leading-relaxed border-blue-500">{{ $post->content }}</p>

                    <!-- Post Image (if present) -->
                    @if($post->image)
                        <div class="mb-5">
                            <img src="{{ asset('storage/' . $post->image) }}"
                                 class="img-fluid rounded-lg shadow-lg w-100"
                                 alt="Post Image">
                        </div>
                    @endif

                    <!-- Actions: Likes + Comments -->
                    <div class="flex items-center gap-6 mb-5 pb-4 border-bottom border-gray-700">
                        <!-- Like (only for members) -->
                        @if(auth()->user()->role === 'member')
                            <form action="{{ route('leader.posts.like.toggle', $post) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn {{ $isLiked ? 'btn-primary' : 'btn-outline-primary' }} d-flex align-items-center gap-2">
                                    <i class="fas fa-thumbs-up"></i>
                                    <span>{{ $post->likes_count }} Like{{ $post->likes_count != 1 ? 's' : '' }} </span>
                                </button>
                            </form>
                        @else
                            <span class="d-flex align-items-center gap-2 text-primary">
                                <i class="fas fa-thumbs-up"></i>
                                {{ $post->likes_count }} Like{{ $post->likes_count > 1 ? 's' : '' }}
                            </span>
                        @endif

                        <!-- Comments -->
                        <span class="d-flex align-items-center gap-2 text-emerald-400">
                            <i class="fas fa-comment"></i>
                            {{ $post->comments_count }} Comment{{ $post->comments_count > 1 ? 's' : '' }}
                        </span>

                        <!-- View Likes -->
                        <button class="btn btn-link text-blue-400 p-0" data-bs-toggle="modal" data-bs-target="#likesModal">
                            View likes
                        </button>
                    </div>

                    <!-- Add Comment Form (only for members) -->
                    @if(auth()->user()->role === 'member')
                        <div class="mb-5">
                            <form action="{{ route('leader.posts.comment.store', $post) }}" method="POST">
                                @csrf
                                <div class="d-flex gap-3">
                                    <div class="flex-grow-1">
                                        <textarea name="content"
                                                  class="form-control bg-gray-800 border-gray-600 text-white"
                                                  rows="3"
                                                  placeholder="Add a comment..."
                                                  required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <button type="submit"
                                                class="btn btn-primary rounded-circle p-3 d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Comments List -->
                    <h5 class="h5 fw-bold mb-4">Comments</h5>
                    @if($post->comments->count() > 0)
                        <div class="space-y-4">
                            @foreach($post->comments as $comment)
                                <div class="d-flex gap-3 bg-gray-800 rounded-lg p-4">
                                    <img src="{{ $comment->user->profile ?? asset('images/user-default.jpg') }}"
                                         alt="Profile"
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 rounded-circle me-3 shadow-sm" width="50" height="50">
                                    <div class="flex-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-white">{{ $comment->user->name }}</strong>
                                                <small class="text-gray-400 ms-2">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <!-- Delete if it's the logged-in user's comment -->
                                            @if(auth()->id() === $comment->user_id)
                                                <form action="{{ route('leader.posts.comment.destroy', $comment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger p-1 rounded"
                                                            onclick="return confirm('Delete this comment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <p class="text-gray-200 mt-2 mb-0">"{{ $comment->content }}"</p>
                                    </div>
                                </div>
                                <br>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No comments yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Likes Modal -->
<div class="modal fade" id="likesModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-gray-900 text-white border-gray-700">
            <div class="modal-header border-gray-700">
                <h5 class="modal-title">People who liked this</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($post->likes->count() > 0)
                    <div class="space-y-3">
                        @foreach($post->likes as $like)
                            <div class="d-flex align-items-center gap-3 bg-gray-800 rounded p-3">
                                <img src="{{ $like->user->profile ?? asset('images/user-default.jpg') }}"
                                     alt="Profile" width="50" height="50"
                                     class="rounded-circle me-3 shadow-sm w-10 h-10 rounded-full object-cover">
                                <div>
                                    <strong>{{ $like->user->name }}</strong>
                                    <small class="text-gray-400 d-block">
                                        {{ $like->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center">No one has liked this post yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- Posts CSS -->
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
@endpush
