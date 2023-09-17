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
//        return $this->bindVariables(file_get_contents($view_filename), $variables);
    }

    private function bindVariables(string $content, array $variables)
    {
        $start_pattern_positions = $this->getAllPatternPositions($content, '{{');
        $end_pattern_positions = $this->getAllPatternPositions($content, '}}');
        $summary_pattern_positions = [];

        foreach ($start_pattern_positions as $start_pattern_index => $start_pattern_position) {
            $pattern = substr($content, $start_pattern_position, $end_pattern_positions[$start_pattern_index] - $start_pattern_position + 2);
            $variable = trim(trim(str_replace(' ', '', $pattern), '{{$'), '}}');

            $summary_pattern_positions[] = [
                'pattern' => $pattern,
                'variable' => $variable
            ];
        }


        foreach ($summary_pattern_positions as $summary_pattern_position) {
            $content = str_replace($summary_pattern_position['pattern'], $variables[$summary_pattern_position['variable']], $content);
        }

        return $content;
    }

    private function getAllPatternPositions(string $content, string $needle)
    {
        $offset = 0;
        $positions = [];
        $position = strpos($content, $needle, $offset);
        while ($position) {
            $positions[] = $position;
            $offset = $position + 1;

            $position = strpos($content, $needle, $offset);
        }

        return $positions;
    }
}