<?php

namespace App\Controllers;

use App\Core\Controller;

class LanguageController extends Controller
{
    public function switchLang($locale)
    {
        // Only allow predefined locales
        $allowed = ['en', 'ar'];
        if (in_array($locale, $allowed)) {
            $_SESSION['locale'] = $locale;
        }
        
        // Redirect back to where the user came from
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }
}
