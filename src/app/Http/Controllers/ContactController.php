<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function register(ContactRequest $request)
    {

        $customerEmail = $request->customerEmail;
        $comment = $request->comment;
        DB::table('contacts')
            ->insert(
                [
                    'customer_email' => $customerEmail
                    , 'comment' => $comment
                    , 'created_at' => now()
                    , 'updated_at' => now()
                ]
            );
    }
}
