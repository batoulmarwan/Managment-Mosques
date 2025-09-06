<?php

namespace App\Http\Controllers;
use App\Models\Staff;
use App\Http\Requests\MosqueStoreRequest;
use App\Http\Resources\MosqueResource;
use App\Models\Mosque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class MosqueController extends BaseController
{
  
    public function store(MosqueStoreRequest $request)
{
    $mosque = new Mosque($request->all());

    if ($request->hasFile('image_path')) {
        $image = $request->file('image_path');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $destinationPath = public_path('mosques');

        $image->move($destinationPath, $imageName);
        $mosque->image_path = 'mosques/' . $imageName;
    }

    $mosque->save();

   $mosque->image_path = $mosque->image_path ? asset( $mosque->image_path) : null;
    return $this->sendResponse($mosque, "Added successfully");
}



   /* public function update(MosqueStoreRequest $request, $id)
    {
        $mosque = Mosque::find($id);
        if (!$mosque) {
            return response()->json(['message' => 'Mosque not found'], 404);
        }
        $mosque->city_id = $request->city_id;
        $mosque->name = $request->name;
        $mosque->address = $request->address;
        $mosque->area = $request->area;
        $mosque->details = $request->details;
        $mosque->technical_status = $request->technical_status;
        $mosque->category = $request->category;
        $mosque->has_female_section = $request->has_female_section;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imagePath = $image->store('mosques', 'public');
            $mosque->image_path = $imagePath;
        }
        $mosque->save();
       $mosque->image_path = $mosque->image_path ? asset($mosque->image_path) : null;
        return $this->sendResponse($mosque, "Added successfully");
    }*/
    public function update(MosqueStoreRequest $request, $id)
{
    $mosque = Mosque::find($id);
    if (!$mosque) {
        return response()->json(['message' => 'Mosque not found'], 404);
    }

    $mosque->city_id = $request->city_id;
    $mosque->name = $request->name;
    $mosque->address = $request->address;
    $mosque->area = $request->area;
    $mosque->details = $request->details;
    $mosque->technical_status = $request->technical_status;
    $mosque->category = $request->category;
    $mosque->has_female_section = $request->has_female_section;

    if ($request->hasFile('image_path')) {
        $image = $request->file('image_path');


        $imageName = time() . '_' . $image->getClientOriginalName();
        $destinationPath = public_path('mosques');
        $image->move($destinationPath, $imageName);

        $mosque->image_path = 'mosques/' . $imageName;
    }

    $mosque->save();

   
  $mosque->image_path = $mosque->image_path ? asset( $mosque->image_path) : null;
    return $this->sendResponse($mosque, "Updated successfully");
}


    public function index()
    {
        $mosques = Mosque::all();
        return $this->sendResponse(MosqueResource::collection($mosques), "Successfully retrieved all mosques.");

    }
    public function searchByCategory(Request $request)
    {
        $category = $request->input('category');

        if (!$category) {
            return $this->sendError('Category is required.');
        }

        $mosques = Mosque::where('category', $category)->get();

        if ($mosques->isEmpty()) {
            return $this->sendError('No mosques found in this category.');
        }

        return $this->sendResponse(MosqueResource::collection($mosques), 'Mosques retrieved successfully.');
    }
    public function searchByName(Request $request)
    {
        $name = $request->input('name');

        if (!$name) {
            return $this->sendError('Name is required.');
        }

        $mosques = Mosque::where('name', $name)->get();

        if ($mosques->isEmpty()) {
            return $this->sendError('No mosques found in this category.');
        }

        return $this->sendResponse(MosqueResource::collection($mosques), 'Mosques retrieved successfully.');
    }
    public function destroy($id)
{
    $mosque = Mosque::findOrFail($id);
    $mosque->delete();

    return $this->sendResponse([], 'Mosque archived successfully');
}
    public function ViewDelate(Request $request){
    $archivedMosques = Mosque::onlyTrashed()->get();
    return $this->sendResponse($archivedMosques, " successfully");
}
public function getMyMosqueName()
{
    $userPhone = auth()->user()->mynumber;

    $staff = Staff::where('phone', $userPhone)->first();

    if (!$staff) {
        return response()->json(['message' => 'المستخدم غير موجود كمشرف'], 404);
    }

    $mosque = $staff->mosques()->first(); 

    if (!$mosque) {
        return response()->json(['message' => 'لا يوجد مسجد مرتبط بالمشرف'], 404);
    }

    return response()->json([
        'mosque_name' => $mosque->name
    ]);
}

}

