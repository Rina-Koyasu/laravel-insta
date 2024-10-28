<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
  private $category;
  private $post;

  public function __construct(Category $category, Post $post)
  {
    $this->category = $category;
    $this->post = $post;
  }

  /*
  public function index()
  {
    $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(10);

    return view('admin.categories.index')->with('all_categories', $all_categories);
  }
*/

//cotegoryの編集、削除の機能ができた後、indexをアップデート
public function index()
{
    $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(10);

    $uncategorized_count = 0;
    $all_posts = $this->post->all();
    foreach($all_posts as $post){
        if($post->categoryPost->count() == 0){
            $uncategorized_count++;
        }
    }
    return view('admin.categories.index')->with('all_categories', $all_categories)->with('uncategorized_count', $uncategorized_count);
}


  //homework にて記述したコード
  /*
  public function store(Request $request)
  {
    //　Add validation
    $request->validate([
      'name' => 'required|min:3|max:50'

    ]);
    // INSERT INTO catogories (name) VALUE ($request->name)
    $this->category->name    = $request->name;

    $this->category->save();
    // Back to homepage
    return redirect()->back();

  }
    */

  //teacher's code

  public function store(Request $request)
  {
    $request->validate([
      'name'      =>      'required|min:1|max:50|unique:categories,name'
    ]);

    // strtolower($request->name)：$request->name の全ての文字を小文字に変換します。これにより、入力がすべて大文字や混在していても、まずは一旦小文字に変換します。ucwords(...)：文字列の各単語の先頭文字を大文字に変換します。たとえば、"hello world" なら "Hello World" になります。

    $this->category->name = ucwords(strtolower($request->name));
    $this->category->save();

    return redirect()->back();
  }


  public function update(Request $request, $id)
  {
    $request->validate([
      'name'      =>      'required|min:1|max:50|unique:categories,name,' . $id
    ]);

    $category       =   $this->category->findOrFail($id);
    $category->name =   ucwords(strtolower($request->name));
    $category->save();

    return redirect()->back();
  }

  public function destroy($id)
  {
    $this->category->destroy($id);
    return redirect()->back();
  }
}
