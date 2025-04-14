<?php

namespace App\Policies\DirectoryCleanup;

use Spatie\DirectoryCleanup\Policies\CleanupPolicy;
use Symfony\Component\Finder\SplFileInfo;

class DefaultCleanupPolicy implements CleanupPolicy
{
    public function shouldDelete(SplFileInfo $file): bool
    {
        $name = $file->getFilename();

        return ! str_contains($name, '.git');

    }
}
