<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function load(Request $request)
    {
        $categories = \App\Models\Category::all();
        return $this->responseSuccess('Categories loaded successfully', $categories);
    }


     public function save(Request $request){
        \App\Models\Category::create([
            'category' => $request->category
        ]);
        return $this->responseSuccess('Categories saved successfully',null);
    }

    public function delete(Request $request){
        $category = \App\Models\Category::find($request->input('id'));
        if($category){
            $category->delete();
            return $this->responseSuccess('Category deleted successfully',null);
        }else{
            return $this->responseError('Category not found',null);
        }
    }
}
