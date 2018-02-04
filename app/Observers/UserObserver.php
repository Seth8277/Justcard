<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function deleting(User $user)
    {
        $orders = $user->orders;
        $orders->update(['user_id' => null]);
    }

}