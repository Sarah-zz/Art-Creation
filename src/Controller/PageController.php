<?php
namespace App\Controller;

class PageController
{
    private string $viewPath;

    public function __construct()
    {
        $this->viewPath = dirname(__DIR__) . '/View';
    }
    public function render(string $page = 'home'): array
    {
        $file = $this->viewPath . '/' . $page . '.php';
        if (!file_exists($file)) {
            http_response_code(404);
            $file = $this->viewPath . '/error404.php';
        }

        return [
            'view' => $file,
            'data' => []
        ];
    }
}
