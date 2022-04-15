<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Git root path
    |--------------------------------------------------------------------------
    |
    | Determine the default Git root path
    | Default is your project base path.
    |
    */

    'git_path' => base_path(),

    /*
    |--------------------------------------------------------------------------
    | Commit base url
    |--------------------------------------------------------------------------
    |
    | Determine the default Git commit url
    | Example for Github: https://github.com/your_username/your_repository/commit/%s
    | Example for Gitlab: https://gitlab.com/your_username/your_repository/-/commit/%s
    |
    */

    'commit_url' => 'https://github.com/your_username/your_repository/commit/%s',
];
