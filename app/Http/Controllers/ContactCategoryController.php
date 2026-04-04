<?php

namespace App\Http\Controllers;

use App\Models\ContactCategory;
use Illuminate\Http\Request;

class ContactCategoryController extends Controller
{
    /** List all categories (JSON for AJAX) */
    public function index()
    {
        $categories = ContactCategory::orderBy('name')->get(['id', 'name', 'is_active']);
        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:contact_categories,name',
            ]);
            $validated['is_active'] = true;
            $category = ContactCategory::create($validated);
            return response()->json(['success' => true, 'message' => 'Category added.', 'data' => $category]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $category  = ContactCategory::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:contact_categories,name,' . $id,
            ]);
            $validated['is_active'] = $request->boolean('is_active', true);
            $category->update($validated);
            return response()->json(['success' => true, 'message' => 'Category updated.', 'data' => $category]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = ContactCategory::findOrFail($id);
            if ($category->contacts()->count() > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete: contacts are using this category.'], 422);
            }
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Unable to delete.'], 500);
        }
    }
}
