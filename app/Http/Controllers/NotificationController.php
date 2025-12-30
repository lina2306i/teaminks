<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //

    // 🔐 Toutes les routes protégées
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 📥 Liste des notifications de l’utilisateur
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:success,info,warning,error',
        'to' => 'required|exists:users,id',
        'notifiable_id' => 'nullable|integer', // pour lier à un modèle
        'notifiable_type' => 'nullable|string', // Task, Project, etc.
    ]);

    Notification::create([
        'title' => $validated['title'],
        'message' => $validated['message'],
        'type' => $validated['type'],
        'user_id' => $validated['to'],
        'from_id' => auth()->id(),
        'notifiable_id' => $validated['notifiable_id'] ?? null,
        'notifiable_type' => $validated['notifiable_type'] ?? null,
    ]);

    return back()->with('success', 'Notification sent successfully');
}

    // 👁️ Marquer une notification comme lue
    public function read(Notification $notification)
    {
        $this->authorizeNotification($notification);

        $notification->markAsRead();

        return back();
    }



    // 👁️‍🗨️ Marquer toutes comme lues
    public function readAll()
    {
        auth()->user()
            ->notifications()
            ->unread()
            ->update(['read' => true]);

        return back();
    }

    // 🧹 Supprimer une notification
    public function destroy(Notification $notification)
    {
        $this->authorizeNotification($notification);

        $notification->delete();

        return back();
    }

    // 🔒 Sécurité : accès propriétaire seulement
    private function authorizeNotification(Notification $notification): void
    {
        abort_if($notification->user_id !== auth()->id(), 403);
    }
}
