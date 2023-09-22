<?php

namespace App\View;

use App\Config;

class View
{
    public string $name;
    private array $variables;
    public string $path;

    public function __construct(string $name, array $variables = [])
    {
        $this->name = $name;
        $this->variables = $variables;
        $this->path = Config::get('view_path');
    }

    public function getHtml()
    {
        $view_filename = str_ends_with('.php', $this->name)
            ? $this->path . '/' . $this->name
            : $this->path . '/' . $this->name . '.php';

        extract($this->variables, EXTR_SKIP);
        include $view_filename;
        return '';
    }
}