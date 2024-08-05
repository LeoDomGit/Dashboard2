<?php

namespace Leo\Post\Controllers;

use App\Http\Controllers\Controller;
use Leo\Post\Models\PostCate;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostCateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postcates= PostCate::all();
        return Inertia::render("PostCates/Index",['postcates'=>$postcates]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:post_collections,name',

        ], [
            'name.required' => 'Chưa nhận được loại bài viết',
            'name.unique' => 'loại bài viết bị trùng',
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $data = $request->all();
        $data['slug']= Str::slug($request->name);
        PostCate::create($data);
        $postcates= PostCate::all();
        return response()->json(['check'=> true,'data'=> $postcates]);
    }

    /**
     * Display the specified resource.
     */
    public function api_index(PostCate $postcate)
    {
        return response()->json(PostCate::active()->orderBy('id','asc')->get());
    }
    public function api_show(PostCate $postcate, $id)
    {
        return response()->json(PostCate::with('posts')->active()->where('slug',$id)->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCate $postcate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostCate $postcate,$id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'unique:post_collections,name',

        ], [
            'name.required' => 'Chưa nhận được loại bài viết',
            'name.unique' => 'Loại bài viết bị trùng',
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $data = $request->all();
        if($request->has('name')){
            $data['slug']= Str::slug($request->name);
        }
        PostCate::where('id',$id)->update($data);
        $postcates=PostCate::all();
        return response()->json(['check'=> true,'data'=> $postcates]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCate $postcates, $id)
    {
        $brand = PostCate::find($id);
        if(!$brand){
            return response()->json(['check'=> true,'msg'=>'Không tìm được loại bài viết']);
        }
        $brand->delete();
        $postcates=PostCate::all();
        return response()->json(['check'=> true,'data'=> $postcates]);
    }
}
