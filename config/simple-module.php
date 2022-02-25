<?php
return [
    /**
     * module directory path
     */
    'directory' => base_path('module'),

    /**
     * module namespace
     */
    'namespace' => 'Module',

    /**
     * module provider filename (classname)
     */
    'classname' => 'Module',

    /**
     * module provider cache path
     */
    'cache' => 'cache/module-cache.php',

    /**
     * denies (deny) module classes
     */
    'denies' => [],

    /**
     * modules (submodule)
     * [ 'namespace' => 'directory' ]
     */
    'modules' => [
        // 'OtherModule' => base_path('submodule'),
    ],
];
