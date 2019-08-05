<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsernamesEmailsDomains() {
        $users = User::all();
        $emails = array();
        $usernames = array();
        $domains = array();
        if ($users) {
            foreach($users as $user){
                 $domain = $user->client->domain;
                array_push($emails, $user->email);
                array_push($usernames, $user->username);
                array_push($domains, $domain);
            }
        }

        return response()->json(['emails' => $emails, 'usernames' => $usernames, 'domains' => $domains], 200);
    }

    public function checkUser() {
        ?>
        <script>
        console.log("I'm doing this fro the controller");
        </script>
        <?php
    }
}


