<?php

function view(string $view, array $data = []): void
{
    extract($data);
    include __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';
}
