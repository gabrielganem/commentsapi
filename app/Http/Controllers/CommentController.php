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

    public function update(Request $request, $id)
    {

        $comment = DB::table('comments')->where('id', $id);

        if (isset($comment)) {
            if (($request->input("name")) != null && $request->input("message") != null) {
                $comment->update([
                'name' => $request->input("name"),
                'message' => $request->input("message"),
            ]);
        }
    }
        
        return redirect()->route('index');
    }

    public function reply(Request $request, $id)
    {
        $parent_comment = DB::table('comments')->where('id', $id)->first();

        if (isset($parent_comment)) {
            $comment = new Comment([
                'name' => $request->input("name"),
                'message' => $request->input("message"),
                'parent_id' => $parent_comment->id,
            ]);
    
            $comment->save();
        }

        return "OK";
    }
}
