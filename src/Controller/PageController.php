<?php
namespace App\Controller;

class PageController
{
    private string $viewPath;

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/View';
    }

    public function render(string $page): array
    {
        return [
            'view' => $this->viewPath . '/' . $page . '.php',
            'data' => []
        ];
    }
}
