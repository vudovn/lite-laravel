<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\User;
use Framework\Request;
use Framework\Response;

class UserController extends Controller
{
    /**
     * Get all users
     *
     * @return string
     */
    public function index()
    {
        $users = (new User())->all();

        header('Content-Type: application/json');
        return json_encode($users);
    }

    /**
     * Get a specific user
     *
     * @param int $id
     * @return string
     */
    public function show($id)
    {
        $user = (new User())->find($id);

        header('Content-Type: application/json');

        if (!$user) {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }

        return json_encode($user);
    }

    /**
     * Create a new user
     *
     * @param Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // Simple validation
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            header('Content-Type: application/json');
            http_response_code(422);
            return json_encode(['error' => 'Missing required fields']);
        }

        if (strlen($data['password']) < 8) {
            header('Content-Type: application/json');
            http_response_code(422);
            return json_encode(['error' => 'Password must be at least 8 characters']);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->save();

        header('Content-Type: application/json');
        http_response_code(201);
        return json_encode($user);
    }

    /**
     * Update a user
     *
     * @param Request $request
     * @param int $id
     * @return string
     */
    public function update(Request $request, $id)
    {
        $user = (new User())->find($id);

        header('Content-Type: application/json');

        if (!$user) {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }

        $data = $request->all();

        // Simple validation
        if (isset($data['password']) && strlen($data['password']) < 8) {
            http_response_code(422);
            return json_encode(['error' => 'Password must be at least 8 characters']);
        }

        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }

        if (!empty($data['email'])) {
            $user->email = $data['email'];
        }

        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $user->save();

        return json_encode($user);
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return string
     */
    public function destroy($id)
    {
        $user = (new User())->find($id);

        header('Content-Type: application/json');

        if (!$user) {
            http_response_code(404);
            return json_encode(['error' => 'User not found']);
        }

        $user->delete();

        return json_encode(['message' => 'User deleted successfully']);
    }
}