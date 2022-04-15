# Laravel Nova Git Manager Tool

This is a package for [Laravel Nova](https://nova.laravel.com/) which allows you to easily manager your Git.

[![Total Downloads](https://poser.pugx.org/mastani/nova-git-manager/downloads)](https://packagist.org/packages/mastani/nova-git-manager)
[![Latest Stable Version](https://poser.pugx.org/mastani/nova-git-manager/v/stable)](https://packagist.org/packages/mastani/nova-git-manager)
[![Latest Unstable Version](https://poser.pugx.org/mastani/nova-git-manager/v/unstable)](https://packagist.org/packages/mastani/nova-git-manager)
[![License](https://poser.pugx.org/mastani/nova-git-manager/license)](https://packagist.org/packages/mastani/nova-git-manager)

## Screeenshots

![Laravel Nova Git Manager Tool](https://raw.githubusercontent.com/mastani/nova-git-manager/master/screenshot.jpg "Laravel Nova Git Manager Tool")

## Requirements

* PHP >= 7.1
* [Laravel](https://laravel.com/) application with [Laravel Nova](https://nova.laravel.com/) installed
* ``` shell_exec ``` function enabled (For running git log/checkout/pull commands). [See usage](https://github.com/mastani/nova-git-manager/blob/3412c1e74014a33966e59be8325e6d3c67396bbd/src/Http/Controllers/NovaGitController.php#L15)

### Installation

Install the package via composer:
```bash
$ composer require mastani/nova-git-manager
```

Register the tool in the `tools` method of the `NovaServiceProvider`:
```
// app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \Mastani\NovaGitManager\NovaGitManager,
    ];
}
```

## Customizations (Optional)

If you want to change the "Git Path" or "Commit URL" you can publish the config file:
```
php artisan vendor:publish --provider="Mastani\NovaGitManager\ToolServiceProvider" --tag="config"
```

Now head over to "config/nova-git-manager.php" and you can change the value:
```
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

'commit_url' => 'https://github.com/your_username/your_repository/commit/%s'
```

## Contributors
[Amin Mastani](https://github.com/mastani)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
