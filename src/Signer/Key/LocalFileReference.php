<?php
declare(strict_types=1);

namespace Lcobucci\JWT\Signer\Key;

use Lcobucci\JWT\Signer\Key;

use function file_exists;
use function strpos;
use function substr;

final class LocalFileReference implements Key
{
    private const PATH_PREFIX = 'file://';

    private $path;
    private $passphrase;

    private function __construct(string $path, string $passphrase)
    {
        $this->path       = $path;
        $this->passphrase = $passphrase;
    }

    /** @throws FileCouldNotBeRead */
    public static function file(string $path, string $passphrase = ''): self
    {
        if (strpos($path, self::PATH_PREFIX) === 0) {
            $path = substr($path, 7);
        }

        if (! file_exists($path)) {
            throw FileCouldNotBeRead::onPath($path);
        }

        return new self($path, $passphrase);
    }

    public function contents(): string
    {
        return self::PATH_PREFIX . $this->path;
    }

    public function passphrase(): string
    {
        return $this->passphrase;
    }
}
