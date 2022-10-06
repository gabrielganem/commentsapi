<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    public function index()
    {
        $comments = DB::table('comments')->select('id', 'name', 'message')->get();

        return $comments;
    }

    public function store(Request $request)
    {
        $comment = new Comment([
            'name' => $request->input("name"),
            'message' => $request->input("message"),
        ]);

        $comment->save();

        return redirect()->route('index');

    }

    public function reply(Request $request, $id)
    {

    }

    public function update(Request $request)
    {

    }

}
