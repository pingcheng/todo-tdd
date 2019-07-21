<?php


namespace Tests\Feature\TodoTests;


use Tests\TestCase;

class TodoApiTestCase extends TestCase
{
    protected function data(array $modifiers = []): array
    {
        return array_merge([
            'content' => 'a simple todo'
        ], $modifiers);
    }
}