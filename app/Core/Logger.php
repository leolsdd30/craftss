<?php
namespace App\Core;

class Logger
{
    /**
     * Get the absolute path to the core app logic file and ensure directory exists.
     */
    private static function getLogFile(): string
    {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        return $logDir . '/app.log';
    }

    /**
     * Core writing mechanism.
     */
    private static function write(string $level, string $message, array $context = []): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = empty($context) ? '' : ' | Context: ' . json_encode($context);
        
        $formattedMessage = sprintf("[%s] %s: %s%s\n", $timestamp, strtoupper($level), $message, $contextStr);

        // Append to our custom log file instead of the servers global error log
        error_log($formattedMessage, 3, self::getLogFile());
    }

    /**
     * Log general informational messages.
     */
    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    /**
     * Log warnings (things that aren't quite exceptions, but need attention).
     */
    public static function warning(string $message, array $context = []): void
    {
        self::write('WARNING', $message, $context);
    }

    /**
     * Log critical errors and exceptions.
     */
    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }
}
