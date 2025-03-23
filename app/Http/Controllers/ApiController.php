<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;

class ApiController extends Controller
{
    public function user(Request $request, $id){
        $user=User::find($id);
        return new UserResource($user);

    }
}
