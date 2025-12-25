<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaderPostController extends Controller
{
    /**
     * List of posts for the leader's team
     */
    public function index()
    {
        // Get the first team of the leader (or null if none)
        $team = Auth::user()->teamsAsLeader()->first();

        // If no team → empty list + message in view
        if (!$team) {
            $posts = collect(); // Empty collection
        } else {
            $posts = $team->posts()
                        ->with('user')
                        ->withCount(['likes', 'comments'])
                        ->latest()
                        ->paginate(10);
        }

        return view('leader.posts.index', compact('posts', 'team'));
    }

    /**
     * Post creation form
     */
    public function create()
    {
        $teams = Auth::user()->teamsAsLeader;

        return view('leader.posts.create', compact('teams'));
    }

    /**
     * Store the post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id'   => 'required|exists:teams,id',
            'title'     => 'nullable|string|max:255',
            'content'   => 'required|string|min:3',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048', // max ~5MB
        ]);

        // Check that the team belongs to the leader
        $team = Auth::user()->teamsAsLeader()->findOrFail($validated['team_id']);

        $data = [
            'user_id' => Auth::id(),
            'team_id' => $team->id,
            'title'   => $validated['title'] ?? null,
            'content' => $validated['content'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('leader.posts.index')->with('success', 'Post published successfully!');
    }

    /**
     * Display a post
     */
    public function show(Post $post)
    {
        // Check that the post belongs to a team of the leader
        if ($post->team && $post->team->leader_id !== Auth::id()) {
            abort(403);
        }
        // Load necessary relations
        $post->load(['user', 'likes.user', 'comments.user']);

        $isLiked = $post->likes->contains('user_id', auth()->id());

        return view('leader.posts.show', compact('post', 'isLiked'));
    }
    // Edit - form
    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id() && ($post->team?->leader_id !== Auth::id())) {
            abort(403);
        }

        $teams = Auth::user()->teamsAsLeader;

        return view('leader.posts.edit', compact('post', 'teams'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        $this->authorizeEdit($post);

        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'title'   => 'nullable|string|max:255',
            'content' => 'required|string|min:3',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        // Vérification équipe
        //Auth::user()->teamsAsLeader()->findOrFail($validated['team_id']);

        // Si l'utilisateur a cliqué sur "Delete image only"
        if ($request->has('action') && $request->action === 'delete_image') {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->update(['image' => null]);
            return redirect()->route('leader.posts.edit', $post)->with('success', 'Image deleted successfully!');
        }

        // Gestion de l'image
        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }



        // Mise à jour
        $post->update([
            'team_id' => $validated['team_id'],
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'image'   => $validated['image'] ?? $post->image,
        ]);

        return redirect()->route('leader.posts.index')->with('success', 'Post updated successfully!');
    }

    // Delete image only
    /*public function destroyImage(Post $post)
    {
        $this->authorizeEdit($post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
            $post->image = null;
            $post->save();
        }

        return redirect()->route('leader.posts.edit', $post)->with('success', 'Image deleted successfully.');
    }*/

    // Méthode helper pour éviter la duplication
    private function authorizeEdit(Post $post)
    {
        if ($post->user_id !== Auth::id() && ($post->team?->leader_id !== Auth::id())) {
            abort(403);
        }
    }


    /**
     * Delete a post (only author or leader can delete)
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && (! $post->team || $post->team->leader_id !== Auth::id())) {
            abort(403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Post deleted.');
    }

    // Toggle Like
    public function toggleLike(Post $post)
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $message = 'Like removed';
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
            $message = 'Post liked!';
        }

        return back()->with('success', $message);
    }

    // Add comment
    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comment added!');
    }

    // Delete comment
    public function destroyComment(PostComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
