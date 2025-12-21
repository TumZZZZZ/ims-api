<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SVUser
{
    public function getWithPagination(array $params)
    {
        $user = Auth::user();
        $search = $params['search'] ?? null;
        return User::with(['image'])
            ->when($search, function($query, $search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('first_name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone_number', 'like', '%'.$search.'%');
            })
            ->where('merchant_id', $user->merchant->id)
            ->where('deleted_at', null)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getById($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function store(array $params)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($params, $user) {
            // Create user
            $newUser = User::create([
                'merchant_id' => $user->merchant->id,
                'first_name' => $params['first_name'],
                'last_name' => $params['last_name'],
                'email' => $params['email'],
                'phone_number' => $params['phone_number'],
                'role' => $params['role'],
                'password' => bcrypt($params['password']),
                'branch_ids' => $params['branch_ids'],
            ]);

            // Save image if exists
            if (request()->hasFile('image')) {
                uploadImage($newUser->_id, 'users', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($newUser->_id, __('created_an_object', ['object' => __('user')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function update($id, array $params)
    {
        mongoDBTransaction(function() use ($id, $params) {
            // Create user
            $user = User::find($id);

            // Update user
            $user->first_name = $params['first_name'];
            $user->last_name = $params['last_name'];
            $user->email = $params['email'];
            $user->phone_number = $params['phone_number'];
            $user->branch_ids = $params['branch_ids'];
            $user->save();

            // Update image if exists
            if (request()->hasFile('image')) {
                uploadImage($user->_id, 'users', request()->file('image'));
                unset($params['image']);
            }

            // Create history
            unset($params['_token']);
            createHistory($user->_id, __('updated_an_object', ['object' => __('user')]), @$user->merchant->id, $user->active_on, $params);
        });
    }

    public function delete($id)
    {
        $user = Auth::user();
        mongoDBTransaction(function() use ($id, $user) {
            $newUser = User::find($id);

            // Soft delete user
            $newUser->deleted_at = now();
            $newUser->save();

            // Create history
            createHistory($user->_id, __('deleted_an_object', ['object' => __('branch')]), @$user->merchant->id, $user->active_on, [
                'category_id' => (string)$newUser->_id,
                'name'        => $newUser->name,
            ]);
        });
    }
}
