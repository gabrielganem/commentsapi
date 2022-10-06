<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

        $map = [];
        $firstLevel = 1; //the initial level of subcomments for checking up subcomments

        foreach ($allComments as $ckey => $comment) //loop for check all of the subcomments
        {
            if (!in_array($ckey, $map))
            {  
                $this->checksSubComments($allComments, $comment, $firstLevel, $map); // call to a recursive function
            }
        }

        return $allComments;


    }

    public function checksSubComments(&$allComments, &$parentComment, int $level, &$map)
    {
        if ($level >= MAX_SUBCOMMENT_LEVEL) {
            return;
        }; // if the function touchs the last level, time to stop

        foreach ($allComments as $ckey => $comment)
        {
             if ($comment->parent_id !== null && $comment->parent_id == $parentComment->id) //loop through all elements to see if it's a match parent
            {
                $this->checksSubComments($allComments, $comment, $level+1, $map);
                array_push($parentComment->comments, $comment); //puts it in the parent's subcomments array
                unset($allComments[$ckey]); //remove it from the main array
                array_push($map, $ckey); //adds to a hash map to avoid uneccessary loop
                return $allComments;
            }  
        }

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'message' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response("Missing arguments", 400);
        }
 

        $comment = DB::table('comments')->insert([
            'name' => $request->input("name"),
            'message' => $request->input("message"),
        ]);

        return $this->index();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'message' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response("Missing arguments", 400);
        }

        $comment = DB::table('comments')->where('id', $id)->limit(1);

        if (isset($comment)) {
            if (($request->input("name")) != null && $request->input("message") != null) {
                $comment->update([
                'name' => $request->input("name"),
                'message' => $request->input("message"),
            ]);
        }
    }
    else return response("Comment not found", 400);
        
        return $this->index();
    }

    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'message' => 'required',
        ]);
 
        if ($validator->fails()) {
            return response("Missing arguments", 400);
        }
        
        $parent_comment = DB::table('comments')->where('id', $id)->first();

        if (isset($parent_comment)) {
            $comment = DB::table('comments')->insert([
                'name' => $request->input("name"),
                'message' => $request->input("message"),
                'parent_id' => $id,
            ]);
        }
        else return response("Comment not found", 400);

        return $this->index();

    }

    public function delete($id)
    {
        $deleted = DB::table('comments')->where('id', $id)->first();
        if (isset($deleted))
            DB::table('comments')->where('id', $id)->delete();
        else return response("Comment not found", 400);
        return $this->index();
    }
}
