<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommentController extends Controller
{
    public function index()
    {
        $allComments = DB::table('comments')->select('id', 'name', 'message', 'parent_id')->get();
       
        foreach ($allComments as $c)
        {
            $c->comments = [];   
        }

        foreach ($allComments as $ckey => $comment)
        {
            foreach ($allComments as $n1key => $n1comment)
            {
                if ($n1comment->parent_id !== null && $n1comment->parent_id == $comment->id)
                {
                    foreach ($allComments as $n2key => $n2comment)
                    {
                        if ($n2comment->parent_id !== null && $n2comment->parent_id == $n1comment->id)
                        {
                            foreach ($allComments as $n3key => $n3comment)
                            {
                                if ($n3comment->parent_id !== null && $n3comment->parent_id == $n2comment->id)
                                {
                                    array_push($n2comment->comments, $n3comment);
                                    unset($allComments[$n3key]);
                                }                               
                            }
                            array_push($n1comment->comments, $n2comment);
                            unset($allComments[$n2key]);
                        }                       
                    }
                    array_push($comment->comments, $n1comment);
                    unset($allComments[$n1key]);
                }
            }
        }

        return $allComments;
    }

    public function store(Request $request)
    {
        $comment = DB::table('comments')->insert([
            'name' => $request->input("name"),
            'message' => $request->input("message"),
        ]);

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
            $comment = DB::table('comments')->insert([
                'name' => $request->input("name"),
                'message' => $request->input("message"),
                'parent_id' => $id,
            ]);
        }
        return "OK";

    }
}
