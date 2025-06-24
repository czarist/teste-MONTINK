<?php

function view(string $view, array $data = []): void
{
    extract($data);
    include __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
}

function renderHeader(string $title = 'ERP'): void
{
    if (!defined('PAGE_TITLE')) {
        define('PAGE_TITLE', $title);
    }
    include __DIR__ . '/../Views/layouts/header.php';
}


function renderFooter(): void
{
    include __DIR__ . '/../Views/layouts/footer.php';
}
