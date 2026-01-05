<?php


if (!function_exists('getFileIconHelper')) {
    /**
     * Retourne l'icône Font Awesome correspondant au type MIME
     */
    function getFileIconHelper($mimeType) {
        if (str_starts_with($mimeType, 'image/')) {
            return 'fas fa-image';
        }
        if (str_contains($mimeType, 'pdf')) {
            return 'fas fa-file-pdf';
        }
        if (str_contains($mimeType, 'word') || str_contains($mimeType, 'document')) {
            return 'fas fa-file-word';
        }
        if (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) {
            return 'fas fa-file-excel';
        }
        if (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) {
            return 'fas fa-file-powerpoint';
        }
        if (str_contains($mimeType, 'zip') || str_contains($mimeType, 'rar') || str_contains($mimeType, 'compressed')) {
            return 'fas fa-file-archive';
        }
        if (str_contains($mimeType, 'video')) {
            return 'fas fa-file-video';
        }
        if (str_contains($mimeType, 'audio')) {
            return 'fas fa-file-audio';
        }
        if (str_contains($mimeType, 'text')) {
            return 'fas fa-file-alt';
        }
        return 'fas fa-file';
    }
}

if (!function_exists('formatBytesHelper')) {
    /**
     * Formate les octets en unités lisibles (KB, MB, GB)
     */
    function formatBytesHelper($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('getFileExtension')) {
    /**
     * Retourne l'extension d'un fichier
     */
    function getFileExtension($filename) {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}

if (!function_exists('isImageFile')) {
    /**
     * Vérifie si le fichier est une image
     */
    function isImageFile($mimeType) {
        return str_starts_with($mimeType, 'image/');
    }
}

if (!function_exists('getFileColor')) {
    /**
     * Retourne une couleur selon le type de fichier (pour badges)
     */
    function getFileColor($mimeType) {
        if (str_starts_with($mimeType, 'image/')) {
            return 'success';
        }
        if (str_contains($mimeType, 'pdf')) {
            return 'danger';
        }
        if (str_contains($mimeType, 'word')) {
            return 'primary';
        }
        if (str_contains($mimeType, 'excel')) {
            return 'success';
        }
        if (str_contains($mimeType, 'zip')) {
            return 'warning';
        }
        if (str_contains($mimeType, 'video')) {
            return 'info';
        }
        return 'secondary';
    }
}
