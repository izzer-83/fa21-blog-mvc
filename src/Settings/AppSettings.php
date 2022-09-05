<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Settings;

    abstract class AppSettings {

        // Main configuration
        const APP_TITLE             = 'FA21-Blog-Project-MVC';
        
        // Router configuration for sub-folders
        const ROUTER_URI_PREFIX        = '/warehouse-manager-mvc';

        // Path for templates and cache
        const TEMPLATE_PATH         = __DIR__ . '/../../templates';
        const TEMPLATE_CACHE        = __DIR__ . '/../../cache';

    }