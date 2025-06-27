<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait LogActivity
{
    protected function logActivity($action, $details = null, $level = 'info')
    {
        $user = Auth::user();
        $userId = $user ? $user->id : 'Sistema';
        $userName = $user ? $user->name : 'Sistema';

        $logMessage = sprintf(
            '[%s] Usuario: %s (ID: %s) - Acción: %s',
            now()->format('Y-m-d H:i:s'),
            $userName,
            $userId,
            $action
        );

        if ($details) {
            $logMessage .= ' - Detalles: ' . json_encode($details);
        }

        switch ($level) {
            case 'error':
                Log::error($logMessage);
                break;
            case 'warning':
                Log::warning($logMessage);
                break;
            case 'info':
            default:
                Log::info($logMessage);
                break;
        }
    }

    protected function logError($action, $error, $details = null)
    {
        $this->logActivity($action, [
            'error' => $error->getMessage(),
            'details' => $details,
            'file' => $error->getFile(),
            'line' => $error->getLine()
        ], 'error');
    }
} 