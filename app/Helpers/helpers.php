<?php

if (!function_exists('taskPriorityLabel')) {
    /**
     * Retourne le label et la classe CSS pour une priorité de tâche (1 à 5)
     */
    function taskPriorityLabel(int $priority): array
    {
        return match($priority) {
            1 => ['label' => 'Urgent',      'class' => 'bg-danger text-white'],
            2 => ['label' => 'High',        'class' => 'bg-warning text-dark'],
            3 => ['label' => 'Normal',      'class' => 'bg-info text-white'],
            4 => ['label' => 'Low',         'class' => 'bg-secondary text-white'],
            5 => ['label' => 'Very Low',    'class' => 'bg-gray-600 text-white'],
            default => ['label' => 'Normal', 'class' => 'bg-info text-white'],
        };
    }
}

// Tu peux ajouter d'autres helpers ici plus tard
if (!function_exists('formatDate')) {
    function formatDate($date, string $format = 'h i d M Y')
    {
        return $date?->format($format) ?? '-';
    }
}
