<?php

namespace Config;

use App\Filters\AuthFilter;
use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Enregistrement des alias de filtres.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => PerformanceMetrics::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,

        // ← Notre filtre d'authentification + rôle
        'auth'          => AuthFilter::class,
    ];

    // Le reste des propriétés garde les valeurs par défaut de CI4.
    // Ne rien ajouter dans $globals / $methods / $filters
    // sauf si vous avez des besoins spécifiques.
}