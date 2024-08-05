<?php

namespace Leo\Post\Controllers;

use App\Http\Controllers\Controller;
use Leo\Post\Models\Post;
use Leo\Post\Models\PostCate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Leo\Products\Models\Products;
use Leo\Post\Models\Links;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('cates')->get();
        $cates = PostCate::active()->select('id','name')->get();
        $products = Products::active()->select('id','name')->get();
        return Inertia::render('Posts/Index', ['posts' => $posts,'cates'=>$cates,'products'=>$products]);
    }

    public function create()
    {
        $postCates = PostCate::active()->get();
        return Inertia::render('Posts/Create', ['postCates' => $postCates]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getProductsList($id){
        $result =Links::where('id_parent',$id)->pluck('id_link');
        return response()->json($result);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title',
            'summary' => 'required',
            'content' => 'required',
            'id_collection' => 'required|exists:post_collections,id',
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,png,gif',
            'products'=>'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }

        $data['title']=$request->title;
        $data['summary']=$request->summary;
        $data['content']=$request->content;
        $data['id_collection']=$request->id_collection;
        $data['slug'] = Str::slug($request->title);
        $file=$request->file('file');
        $imageName = $file->getClientOriginalName();
        $extractTo = storage_path('app/public/posts/');
        $data['image']=$imageName;
        $file->move($extractTo, $imageName);
        $data['created_at']=now();
        $id=Post::insertGetId($data);
        $products=$request->products;
        foreach ($products as $key => $value) {
            Links::create(['id_link'=>$value,'id_parent'=>$id,'type_1'=>'POST','type_2'=>'PRODUCTS','created_at'=>now()]);
        }
        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $postCates = PostCate::all();
        return Inertia::render('Posts/Edit', ['post' => $post, 'postCates' => $postCates]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('title')) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $imageName = $file->getClientOriginalName();
            $extractTo = storage_path('app/public/posts/');
            $data['image'] = $imageName;
            $file->move($extractTo, $imageName);

            $oldImage = Post::where('id', $id)->value('image');
            $oldFilePath = "public/posts/{$oldImage}";
            Storage::delete($oldFilePath);
        }

        $data['updated_at'] = now();
        unset($data['products']);
        unset($data['file']);
        Post::where('id', $id)->update($data);
        if($request->has('products')){
            $products=$request->products;
            Links::where('id_parent',$id)->where('type_1','POST')->where('type_2','PRODUCTS')->delete();
            foreach ($products as $key => $value) {
                Links::create(['id_link'=>$value,'id_parent'=>$id,'type_1'=>'POST','type_2'=>'PRODUCTS','created_at'=>now()]);
            }
        }
        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function api_highlight(Post $post){
        $result=Post::with('cates')
        ->highlight()
        ->orderBy('id', 'desc')
        ->first();
        return response()->json($result);
    }

    public function api_get(Post $post){
        $result=Post::with('cates')->active()->orderBy('id','desc')->paginate(4);
        return response()->json($result);
    }
    public function api_single(Post $post,$id){
        $result=Post::with('cates')->active()->where('slug',$id)->first();
        $id_collection= $result->id_collection;
        $posts = Post::with('cates')->active()->where('id_collection',$id_collection)->get();
        $id_post=$result->id;
        $products = Products::join('links','links.id_link','=','products.id')
                ->join('gallery','products.id','=','gallery.id_parent')
                ->where('links.id_parent',$id_post)->where('products.status',1)
                ->where('gallery.status',1)
                ->select('products.*','gallery.image as image')->get();
        return response()->json(['post'=>$result,'products'=>$products ,'relative'=>$posts]);

    }
}

