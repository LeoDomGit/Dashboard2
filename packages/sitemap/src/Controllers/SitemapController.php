<?php

namespace Leo\Sitemap\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HasCrud;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Leo\Sitemap\Models\Sitemap;

class SitemapController extends Controller
{
    protected $model = Sitemap::class;

    use HasCrud;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sitemap = Sitemap::all();
        return Inertia::render("Sitemap/Index", ['sitemap' => $sitemap]);
    }

    /**
     * Show the form for creating a new resource.
     */
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
            'page' => 'required',
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $data = $request->all();
        $data['created_at'] = now();
        Sitemap::create($data);
        $result = Sitemap::select('id', 'page', 'static_page', 'content', 'url', 'status')->get();
        return response()->json(['check' => true, 'data' => $result]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $result = Sitemap::find($id)->get();
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sitemap = Sitemap::find($id);
        if (!$sitemap) {
            return response()->json(['check' => false, 'msg' => 'Không tìm thấy mã page']);
        }
        $data = $request->all();
        $data['updated_at'] = now();
        Sitemap::where('id', $id)->update($data);
        $result = Sitemap::select('id', 'page', 'static_page', 'content', 'url', 'status')->get();
        return response()->json(['check' => true, 'data' => $result]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function api_index(Request $request)
    {
        $result=Sitemap::active()->get();
        return response()->json($result);
    }   

    public function api_single(Request $request,$page)
    {
        $result=Sitemap::active()->where('page',$page)->first();
        return response()->json($result);
    }
}
