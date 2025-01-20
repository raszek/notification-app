<?php

namespace App\Service;


readonly class ProjectDirectoryService
{

    public function __construct(
        private string $rootPath,
    ) {
    }

    public function varDirectory(): string
    {
        return $this->rootPath.'/var';
    }

}
