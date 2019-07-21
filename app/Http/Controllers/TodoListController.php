<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiExceptions\ApiForbiddenException;
use App\Todo\Todo;
use Exception;
use Illuminate\Validation\ValidationException;

class TodoListController extends Controller
{

    public function __construct()
    {
        $this->middleware('api', [
            'except' => [
                'index'
            ]
        ]);
        $this->middleware('auth');
    }

    public function index()
    {
        return view('todo.index');
    }

    /**
     * @throws ValidationException
     */
    public function store(): void
    {
        Todo::create(array_merge(
            $this->validateRequest(),
            [
                'user_id' => auth()->id(),
            ]
        ));
    }

    /**
     * @param Todo $todo
     *
     * @throws ValidationException
     * @throws ApiForbiddenException
     */
    public function update(Todo $todo): void
    {
        $current_user_id = auth()->id();
        if ($todo->user_id !== $current_user_id) {
            throw new ApiForbiddenException();
        }

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
