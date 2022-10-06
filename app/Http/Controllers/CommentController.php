<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

define("MAX_SUBCOMMENT_LEVEL", 3);


class CommentController extends Controller
{


    public function index()
    {
        $allComments = DB::table('comments')->select('id', 'name', 'message', 'parent_id')->get(); //getting all the records from the database;
       
        foreach ($allComments as $c)
        {
            $c->comments = [];  //Creating the subComments array inside each one of the comments 
        }

        $level = 0; //the initial level of subcomments for checking up subcomments

        foreach ($allComments as $ckey => $comment) //loop for check all of the subcomments
        {
            $this->checksSubComments($allComments, $comment, $level); // call to a recursive function
        }

        return $allComments;
    }

    public function checksSubComments($allComments, $parentComment, $level)
    {
        if ($level > MAX_SUBCOMMENT_LEVEL) return; // if the function touchs the last level, time to stop
        foreach ($allComments as $ckey => $comment)
        {
             if ($comment->parent_id !== null && $comment->parent_id == $parentComment->id) //loop through all elements to see if it's a match parent
            {
                $this->checksSubComments($allComments, $comment, $level+1);
                array_push($parentComment->comments, $comment); //puts it in the parent's subcomments array
                unset($allComments[$ckey]); //remove it from the main array
            }  
        }

        return;
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
