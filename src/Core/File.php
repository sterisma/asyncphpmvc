<?php

namespace App\Core;

/** simple File DTO */
class File
{
    public string $name;
    public string $mimeType;
    public string $content;

    public function __construct(string $name, string $content, string $mimeType)
    {
        $this->name = $name;
        $this->mimeType = $mimeType;
        $this->content = $content;
    }
}