<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::onlyParent()->with('descendants')->get();
        return view('categories.index', compact('categories'));
    }

    public function select(Request $request)
    {
        $categories = [];
        if ($request->has('q')) {
            $search = $request->q;
            $categories = Category::select('id', 'title')->where('title', 'LIKE', "%$search%")->limit(6)->get();
        } else {
            $categories = Category::select('id', 'title')->onlyParent()->limit(6)->get();
        }

        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // proses validasi data kategori
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:60',
                'slug' => 'required|string|unique:categories,slug',
                // 'thumbnail' => 'required',
                'description' => 'required|string|max:240',
            ],
            [],
            $this->attribut()
        );

        if ($validator->fails()) {
            if ($request->has('parent_category')) {
                $request['parent_category'] = Category::select('id', 'title')->find($request->parent_category);
            }
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // proses insert data kategori
        try {
            Category::create([
                'title' => $request->title,
                'slug' => $request->slug,
                'thumbnail' => parse_url($request->thumbnail)['path'],
                'description' => $request->description,
                'parent_id' => $request->parent_category
            ]);
            Alert::success(
                trans('categories.alert.create.title'),
                trans('categories.alert.create.message.success')
            );
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            if ($request->has('parent_category')) {
                $request['parent_category'] = Category::select('id', 'title')->find($request->parent_category);
            }
            Alert::error(
                trans('categories.alert.create.title'),
                trans('categories.alert.create.message.error', ['error' =>  $th->getMessage()])
            );
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // proses validasi data kategori
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string|max:60',
                'slug' => 'required|string|unique:categories,slug,' . $category->id,
                // 'thumbnail' => 'required',
                'description' => 'required|string|max:240',
            ],
            [],
            $this->attribut()
        );

        if ($validator->fails()) {
            if ($request->has('parent_category')) {
                $request['parent_category'] = Category::select('id', 'title')->find($request->parent_category);
            }
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // proses update data kategori
        try {
            $category->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'thumbnail' => parse_url($request->thumbnail)['path'],
                'description' => $request->description,
                'parent_id' => $request->parent_category
            ]);
            Alert::success(
                trans('categories.alert.update.title'),
                trans('categories.alert.update.message.success')
            );
            return redirect()->route('categories.index');
        } catch (\Throwable $th) {
            if ($request->has('parent_category')) {
                $request['parent_category'] = Category::select('id', 'title')->find($request->parent_category);
            }
            Alert::error(
                trans('categories.alert.update.title'),
                trans('categories.alert.update.message.error', ['error' =>  $th->getMessage()])
            );
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            Alert::success(
                trans('categories.alert.delete.title'),
                trans('categories.alert.delete.message.success')
            );
        } catch (\Throwable $th) {
            Alert::error(
                trans('categories.alert.delete.title'),
                trans('categories.alert.delete.message.error', ['error' =>  $th->getMessage()])
            );
        }

        return redirect()->back();
    }

    public function attribut()
    {
        return [
            'title' => trans('categories.form_control.input.title.attribute'),
            'slug' => trans('categories.form_control.input.slug.attribute'),
            'thumbnail' => trans('categories.form_control.input.thumbnail.attribute'),
            'description' => trans('categories.form_control.textarea.description.attribute'),
        ];
    }
}