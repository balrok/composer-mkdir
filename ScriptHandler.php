<?php
namespace Balrok\ComposerMkdir;

use Composer\Script\Event;
use InvalidArgumentException;
use Exception;

class ScriptHandler
{

    public static function mkdirs(Event $event)
    {
        $extras = $event->getComposer()
            ->getPackage()
            ->getExtra();

        if (!isset($extras['mkdir']) && !isset($extras['symlink'])) {
            $message = 'The mkdir handler needs to be configured through the extra.mkdir or extra.symlink setting.';
            throw new InvalidArgumentException($message);
        }

        if (isset($extras['mkdir'])) {
            self::mkdir($extras['mkdir']);
        }

        if (isset($extras['symlink'])) {
            self::symlink($extras['symlink']);
        }
    }

    public static function mkdir($mkdir)
    {
        if (!is_array($mkdir)) {
            $message = 'The extra.mkdir setting must be an array.';
            throw new InvalidArgumentException($message);
        }
        foreach ($mkdir as $directory => $mode) {
            if (!is_string($mode)) {
                // because 0777 is not a valid json number..
                $message = 'The extra.mkdir requires to have the mode as string e.g.: "dir": "0777".';
                throw new InvalidArgumentException($message);
            }
        }

        $oldmask = umask(0);
        foreach ($mkdir as $directory => $mode) {
            if (!file_exists($directory)) {
                if (!@mkdir($directory, octdec($mode), true)) {
                    throw new Exception("Error with composer-mkdir:mkdir $directory");
                }
            }
        }
        umask($oldmask);
    }

    public static function symlink($symlink)
    {
        if (!is_array($symlink)) {
            $message = 'The extra.symlink setting must be an array.';
            throw new InvalidArgumentException($message);
        }

        foreach ($symlink as $path => $link) {
            if (!file_exists($path)) {
                if (!@symlink($link, $path)) {
                    throw new Exception("Error with composer-mkdir:symlink $link -> $path");
                }
            }
        }
    }
}
