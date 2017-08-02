Composer mkdir
==============

This tool allows you to create directories and symlinks when a composer install or update is run.


Usage
-----

```json
{
    "require": {
        "balrok/composer-mkdir": "^2.1"
    },
    "scripts": {
        "post-install-cmd": [
            "Balrok\\ComposerMkdir\\ScriptHandler::mkdirs"
        ],
        "post-update-cmd": [
            "Balrok\\ComposerMkdir\\ScriptHandler::mkdirs"
        ]
    },
    "extra": {
        "mkdir": {
            "var/cache": "0777",
            "runtime": "0777"
        },
        "symlink": {
            "css": "themes/summer/css",
            "assets/fonts": "../themes/summer/css/fonts"
        }
    }
}
```

For mkdir: parent directories are created if required.


This project builds on top of https://github.com/fbourigault/composer-mkdir - which does not support permissions and symlinks but has unit
tests.
