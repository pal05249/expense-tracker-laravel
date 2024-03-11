<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryPostRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories =  Category::all();
        return response()->json(['categories' => $categories], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CategoryPostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryPostRequest $request)
    {
            $categories = new Category();
            $categories->insert([
                'category_name' => $request->category_name
            ]);
            return response()->json(['message' => 'Successfully added'], 201);
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CategoryPostRequest  $request
     * @param  integer  $categoryId
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryPostRequest $request, $categoryId)
    {
        Category::where('id', $categoryId)->update(['category_name'=> $request->category_name]);
    
        return response()->json(['message'=>'Updated successfully'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer  $categoryId
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId)
    {
        //
       
        $categoryModel = Category::find($categoryId);
        //check if expense exist for the given category before delete
        $categoryModel->checkIfExpenseExistForCategory($categoryId);
        $categoryModel->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
