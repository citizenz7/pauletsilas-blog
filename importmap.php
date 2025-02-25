<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'home' => [
        'path' => './assets/js/home.js',
        'entrypoint' => true,
    ],
    'article' => [
        'path' => './assets/js/article.js',
        'entrypoint' => true,
    ],
    'articles' => [
        'path' => './assets/js/articles.js',
        'entrypoint' => true,
    ],
    'category' => [
        'path' => './assets/js/category.js',
        'entrypoint' => true,
    ],
    'login' => [
        'path' => './assets/js/login.js',
        'entrypoint' => true,
    ],
    'register' => [
        'path' => './assets/js/register.js',
        'entrypoint' => true,
    ],
    'reset-password' => [
        'path' => './assets/js/reset-password.js',
        'entrypoint' => true,
    ],
    'contact' => [
        'path' => './assets/js/contact.js',
        'entrypoint' => true,
    ],
    'apropos' => [
        'path' => './assets/js/apropos.js',
        'entrypoint' => true,
    ],
    'recherche' => [
        'path' => './assets/js/recherche.js',
        'entrypoint' => true,
    ],
    'cgu' => [
        'path' => './assets/js/cgu.js',
        'entrypoint' => true,
    ],
    'confidentialite' => [
        'path' => './assets/js/confidentialite.js',
        'entrypoint' => true,
    ],
    'mentions' => [
        'path' => './assets/js/mentions.js',
        'entrypoint' => true,
    ],
    'comment' => [
        'path' => './assets/js/comment.js',
        'entrypoint' => true,
    ],
    'user' => [
        'path' => './assets/js/user.js',
        'entrypoint' => true,
    ],
    'admin' => [
        'path' => './assets/js/admin.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ]
];
