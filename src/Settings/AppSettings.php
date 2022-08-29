<?php

    namespace App\Settings;

    abstract class AppSettings {

        // Main configuration
        const APP_TITLE             = 'FA21-Blog-Project-MVC';
        
        // Router configuration for sub-folders
        const ROUTER_URI_PREFIX        = '/blog-oop';

        // Path for templates and cache
        const TEMPLATE_PATH         = __DIR__ . '/../../templates';
        const TEMPLATE_CACHE        = __DIR__ . '/../../cache';

    }