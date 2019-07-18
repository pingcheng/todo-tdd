<?php

namespace App\Http\Controllers;

use App\Todo\Todo;
use Exception;
use Illuminate\Validation\ValidationException;

class TodoListController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(): void
    {
        Todo::create($this->validateRequest());
    }

    /**
     * @param Todo $todo
     *
     * @throws ValidationException
     */
    public function update(Todo $todo): void
    {
        $todo->update($this->validateRequest());
    }

    /**
     * @param Todo $todo
     *
     * @throws Exception
     */
    public function delete(Todo $todo): void
    {
        $todo->delete();
    }

    /**
     * @return mixed
     * @throws ValidationException
     */
    protected function validateRequest()
    {
        return $this->validate(request(), [
            'content' => 'required|max:255'
        ]);
    }
}
