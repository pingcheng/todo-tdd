<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiExceptions\ApiException;
use App\Exceptions\ApiExceptions\ApiForbiddenException;
use App\Todo\Todo;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use PingCheng\ApiResponse\ApiResponse;

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
     * @return Response
     * @throws ApiException
     */
    public function list(): Response
    {
        $after = filter_var(request()->get('after', 0), FILTER_VALIDATE_INT);
        if ($after === false) {
            throw new ApiException('after must be an integer');
        }

        if ($after < 0) {
            $after = 0;
        }

        $user = auth()->user();

        $todo_items = Todo::where('user_id', $user->id)
            ->where('id', '>', $after)
            ->take((new Todo)->getPerPage())
            ->get();

        return ApiResponse::ok($todo_items);
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
