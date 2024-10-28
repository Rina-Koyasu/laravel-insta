<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  private $comment;

  public function __construct(Comment $comment)
  {

    $this->comment = $comment;
  }

  public function store(Request $request, $post_id)
  {
    $request->validate(
      [

        'comment_body'  .  $post_id => 'required|max:150'
      ],
      [
        'comment_body'  .  $post_id . '.required' => 'You cannot submit an empty comment.',
        'comment_body'  .  $post_id . '.max' => 'The comment must not have more than 150 characters.'
      ]

    );

    $this->comment->body     = $request->input('comment_body' . $post_id);
    $this->comment->user_id  = Auth::user()->id;
    $this->comment->post_id  = $post_id;
    $this->comment->save();

    return redirect()->route('post.show', $post_id);
  }

  public function destroy($id)
  //関数(method)名は任意で決めている：destroy関数
  {

    $this->comment->destroy($id);
    //このdestroyはlaravelに組み込まれた関数(method)
     return redirect()->back();


    //なぜ$post_idでは機能しないのか？$comment_idだとOKなのに　←　いや$post_idでも機能する
    //route,controller,viewでそれぞれ異なる変数名を付けても機能するが、routeとcontrollerの変数名は可読性と保守性基づき一致させた方が望ましい
    //異なる変数名でも機能する理由：変数は渡される順序に依存しているため、実際の値は、変数を記述する順番で渡されている

    //redirect()->route('index');だとcomments.blabe.php 、show.blabe.phpどちらを消しても　show.blade.phpに戻る
    //redirect()->back();だと、comments.blabe.php、show.blabe.php　各々のページにとどまっている
    //この場合、return redirect()->back();を使う
  }
}
