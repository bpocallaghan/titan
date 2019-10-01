<?php

use App\User;
use Bpocallaghan\Titan\Models\Role;

if (!function_exists('notify_admins')) {
    function notify_admins($class, $argument, $forceEmail = "")
    {
        if (strlen($forceEmail) >= 2) {
            $admins = User::where('email', $forceEmail)->get();
        }
        else {
<<<<<<< HEAD
            $admins = User::whereRole(Role::$ADMIN_SUPER)->get();
=======
            $admins = User::whereRole(Role::$ADMIN_NOTIFY)->get();
>>>>>>> 9c2152ebf10d1698393b412622c7ce2a3fbd33a5
        }

        if ($admins) {
            foreach ($admins as $a => $admin) {
                $admin->notify(new $class($argument));
            }
        }
    }
}

if (!function_exists('notify_users_by_role')) {
    function notify_users_by_role($class, $argument, $role = "admin")
    {
        $users = User::whereRole($role)->get();
        foreach ($users as $k => $user) {
            $user->notify(new $class($argument));
        }
    }
}