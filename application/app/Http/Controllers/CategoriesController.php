<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CreateCategoryRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return response(['status' => 'Ok', 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = new Category();
        $category = $category->add($request->all());
        return empty($category->getAttributes()) ? response(['status' => 'bad request'], 400) :
            response(['status' => 'created', 'category' => $category], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (!$category)
            return response(['status' => 'not found'], 404);
        $category->tasks;
        return response(['status' => 'ok', 'category' => $category], 200);
    }
}
