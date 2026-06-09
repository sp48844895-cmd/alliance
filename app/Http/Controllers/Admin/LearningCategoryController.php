<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LearningCategoryController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.learning-main-cats.index');
    }

    public function store(): RedirectResponse
    {
        return redirect()->route('admin.learning-main-cats.index');
    }

    public function edit($id): RedirectResponse
    {
        $category = \Illuminate\Support\Facades\DB::table('learning_cat')->where('id', $id)->first();
        if (! $category) {
            abort(404);
        }

        if ($category->parent_id) {
            return redirect()->route('admin.learning-sub-cats.edit', $id);
        }

        return redirect()->route('admin.learning-main-cats.edit', $id);
    }

    public function update(): RedirectResponse
    {
        return redirect()->route('admin.learning-main-cats.index');
    }

    public function toggleStatus($id): RedirectResponse
    {
        $category = \Illuminate\Support\Facades\DB::table('learning_cat')->where('id', $id)->first();
        if (! $category) {
            abort(404);
        }

        if ($category->parent_id) {
            return redirect()->route('admin.learning-sub-cats.index');
        }

        return redirect()->route('admin.learning-main-cats.index');
    }

    public function destroy(): RedirectResponse
    {
        return redirect()->route('admin.learning-main-cats.index');
    }
}
