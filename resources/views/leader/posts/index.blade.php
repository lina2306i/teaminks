@extends('layouts.appW')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/stylePosts.css') }}">
@endpush

@section('contentW')

    <section class="py-5 min-vh-100">
        <div class="container">
            <!-- Header: Title + New Post Button -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h1 class="display-5 fw-bold text-white mb-1">Posts</h1>
                    <p class="text-gradient h4 mb-0">Team News Feed</p>
                </div>
                <a href="{{ route('leader.posts.create') }}" class="btn btn-info btn-lg shadow">
                    <i class="fas fa-plus me-2"></i> New Post
                </a>
            </div>

            <!-- Case 1: No team created -->
            @if(!$team)
                <div class="text-center py-10">
                    <i class="fas fa-users fa-5x text-gray-600 mb-4"></i>
                    <h3 class="text-gray-400 mb-3">You haven't created a team yet</h3>
                    <p class="text-gray-500 mb-4">Create a team to start posting and collaborating!</p>
                    <a href="{{ route('leader.team.index') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-plus me-2"></i> Create Your First Team
                    </a>
                </div>

            <!-- Case 2: Team exists but no posts -->
            @elseif($posts->count() === 0)
                <div class="text-center py-10">
                    <i class="fas fa-newspaper fa-5x text-gray-600 mb-4"></i>
                    <h3 class="text-gray-400 mb-3">No posts yet</h3>
                    <p class="text-gray-500 mb-4">Be the first to publish something in your team!</p>
                    <a href="{{ route('leader.posts.create') }}" class="btn btn-info btn-lg">
                        Publish your first post
                    </a>
                </div>

            <!-- Case 3: There are posts → Normal display -->
            @else
                <div class="row g-4 justify-content-center">
                    @foreach($posts as $post)
                        <div class="col-lg-8 col-xl-7">
                            <div class="card bg-gray-800 text-white shadow-lg border-0 rounded-xl hover:shadow-xl hover:border-blue-500 transition-all">
                                <div class="card-body p-5">
                                    <!-- Author + Date + Actions (Edit/Delete) class="w-12 h-12 rounded-full border-2 border-blue-500 object-cover"> -->
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $post->user->profile ?? asset('images/user-default.jpg') }}"
                                                alt="Profile"
                                                   class="rounded-circle me-3 shadow-sm" width="50" height="50">

                                            <div>
                                                <strong class="fw-bold">{{ $post->user->name }}</strong>
                                                <small class="text-gray-400 d-block">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $post->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Edit / Delete Buttons (if author or leader) -->
                                        @if(auth()->id() === $post->user_id || ($post->team && $post->team->leader_id === auth()->id()))
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('leader.posts.edit', $post) }}"
                                                class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('leader.posts.destroy', $post) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Delete this post?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Post Title -->
                                    <h3 class="h4 fw-bold mb-3">
                                        <a href="{{ route('leader.posts.show', $post) }}"
                                        class="text-white hover:text-blue-400 transition">
                                            {{ $post->title ?? 'Untitled Post' }}
                                        </a>
                                    </h3>

                                    <!-- Content -->
                                    <p class="text-gray-200 text-lg mb-4">{{ $post->content }}</p>

                                    <!-- Image if present -->
                                    @if($post->image)
                                        <div class="mb-4">
                                            <img src="{{ asset('storage/' . $post->image) }}"
                                                class="img-fluid rounded-lg shadow d-flex align-items-center justify-content-center"
                                                alt="Image du post de {{ $post->user->name ?? 'l\'utilisateur' }}" style="max-height: 300px; border-radius: 10px; border: 2px solid darkblue;"
                                                onerror="this.style.display='none'"> <!-- cache l'image si elle ne charge pas -->
                                        </div>
                                    @endif

                                    <!-- Quick Actions (Likes, Comments, View) -->
                                    <div class="d-flex align-items-center gap-5 text-gray-400">
                                        <a href="{{ route('leader.posts.show', $post) }}"
                                        class="d-flex align-items-center gap-2 hover:text-blue-400 transition">
                                            <i class="fas fa-thumbs-up"></i>
                                            {{ $post->likes_count }} Like{{ $post->likes_count != 1 ? 's' : '' }}
                                        </a>
                                        <a href="{{ route('leader.posts.show', $post) }}"
                                        class="d-flex align-items-center gap-2 hover:text-emerald-400 transition">
                                            <i class="fas fa-comment"></i>
                                            {{ $post->comments_count }} Comment{{ $post->comments_count != 1 ? 's' : '' }}
                                        </a>
                                        <a href="{{ route('leader.posts.show', $post) }}"
                                        class="hover:text-blue-400 transition ms-auto">
                                            View post →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Create Post Modal unitil -->
    @include('leader.posts._create_modal')
    <!-- Edit Post Modal (empty, filled with JS) -->
    <div class="modal fade" id="editPostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-gray-900 text-white border-gray-700">
                <form id="editPostForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-gray-700">
                        <h5 class="modal-title">Edit Post Model</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit-title" class="form-control bg-gray-800 border-gray-600 text-white" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content</label>
                            <textarea name="content" id="edit-content" rows="5" class="form-control bg-gray-800 border-gray-600 text-white" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-gray-700">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function fillEditModal(post) {
        document.getElementById('edit-title').value = post.title || '';
        document.getElementById('edit-content').value = post.content || '';
        document.getElementById('editPostForm').action = `/leader/posts/${post.id}`;
    }
</script>
@endpush
