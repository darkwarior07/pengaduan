<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class PostController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:post_show', ['only' => 'index']);
    //     $this->middleware('permission:post_create', ['only' => ['create','store']]);
    //     $this->middleware('permission:post_update', ['only' => ['edit','update']]);
    //     $this->middleware('permission:post_detail', ['only' => 'show']);
    //     $this->middleware('permission:post_delete', ['only' => 'destroy']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create', [
            'categories' => Category::with('descendants')->onlyParent()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validator = Validator::make(
                $request->all(),
                [
                'title' => 'required|string|max:60',
                'slug' => 'required|string|unique:posts,slug',
                'thumbnail' => 'required',
                'description' => 'required|string|max:240',
                'content' => 'required',
                'category' => 'required',
               

                ],
                [],
                $this->attributes()
                );
                if ($validator->fails()){
                    if($request['tag']){
                        $request['tag'] = Tag::select('id', 'title')->whereIn('id', $request->tag)->get();
                    }
                    return redirect()->back()->withInput($request->all())->withErrors($validator);
                }
                DB::beginTransaction();
            try {
                $post = Post::create([
                    "title" => $request->title,
                    "slug" => $request->slug,
                    "thumbnail" => parse_url($request->thumbnail)['path'],
                    "description" => $request->description,
                    "content" => $request->content,
                    "user_id" => Auth::user()->id,
                ]);
                $post->tags()->attach($request->tag);
                $post->categories()->attach($request->category);

                Alert::success(
                    trans('create post'),
                    trans('success'),
                );
                return redirect()->route('posts.index');
            } catch (\Throwable $th) {
                DB::rollBack();
                Alert::error(
                    trans('create post'),
                    trans('error',['error' => $th->getMessage()]),
                );
                if($request['tag']){
                    $request['tag'] = Tag::select('id', 'title')->whereIn('id', $request->tag)->get();
                }
                return redirect()->back()->withInput($request->all())->withErrors($validator);
                //throw $th;
            }finally{
                DB::commit();
            }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $categories = $post->categories;
        $tags = $post->tags;
        return view('posts.detail', compact('post','categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit',['post' => $post,
        'categories' => Category::with('descendants')->onlyParent()->get()

    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'title' => 'required|string|max:60',
            'slug' => 'required|string|unique:posts,slug,'.$post->id,
            'thumbnail' => 'required',
            'description' => 'required|string|max:240',
            'content' => 'required',
            'category' => 'required',
    
            ],
            [],
            $this->attributes()
            );
           
            DB::beginTransaction();
            try {
                $post->update([
                    "title" => $request->title,
                    "slug" => $request->slug,
                    "thumbnail" => parse_url($request->thumbnail)['path'],
                    "description" => $request->description,
                    "content" => $request->content,
                    "user_id" => Auth::user()->id,
                ]);
                $post->tags()->sync($request->tag);
                $post->categories()->sync($request->category);

                Alert::success(
                    trans('Edit post'),
                    trans('success'),
                );
                return redirect()->route('posts.index');
            } catch (\Throwable $th) {
                DB::rollBack();
                Alert::error(
                    trans('Edit post'),
                    trans('error',['error' => $th->getMessage()]),
                );
                if($request['tag']){
                    $request['tag'] = Tag::select('id', 'title')->whereIn('id', $request->tag)->get();
                }
                return redirect()->back()->withInput($request->all())->withErrors($validator);
                //throw $th;
            }finally{
                DB::commit();
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        DB::beginTransaction();
        try {
            $post->tags()->detach();
            $post->categories()->detach();
            $post->delete();


            Alert::success(
                trans('Delete post'),
                trans('success'),
            );
            return redirect()->route('posts.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            Alert::error(
                trans('Delete post'),
                trans('error',['error' => $th->getMessage()]),
            );
        }finally{
            DB::commit();
            return redirect()->back();
        }
    }
   

    private function attributes() 
    {
       return [
                'title' => trans('Title error'),
                'slug' => trans('Slug error'),
                'thumbnail' => trans('Thumbnail error'),
                'description' => trans('Description error'),
                'content' => trans('Content error'),
                'category' =>trans('Category error'),
                
        ];
    }
}
