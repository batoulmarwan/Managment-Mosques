<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreMosqueManagerRequest;
use App\Http\Requests\UpdateMosqueManagerRequest;
use App\Http\Resources\StaffResource;
use App\Http\Resources\ManagmentResource;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Staff_mosque;
use App\Models\Mosque;
class ShaffController extends BaseController
{
    public function addMosqueManager(StoreMosqueManagerRequest $request)
    {
        $validated = $request->validated();

        $staff = Staff::create([
            'full_name' => $validated['full_name'],
            'mother_name' => $validated['mother_name'],
            'birth_date' => $validated['birth_date'],
            'national_id' => $validated['national_id'],
            'address' => $validated['address'],
            'previous_job' => $validated['previous_job'] ?? null,
            'education_level' => $validated['education_level'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);
                Staff_mosque::create([
            'mosque_id' => $validated['mosque_id'],
            'staff_id' => $staff->id,
            'role' => $validated['role'],
        ]);
        return $this->sendResponse(new StaffResource($staff), "Successfully retrieved all mosques.");
    }

    public function updateMosqueManager(UpdateMosqueManagerRequest $request, $id)
    {
     $validated = $request->validated();
     $staff = Staff::findOrFail($id);
     $staff->update([
        'full_name' => $validated['full_name'],
        'mother_name' => $validated['mother_name'],
        'birth_date' => $validated['birth_date'],
        'national_id' => $validated['national_id'],
        'address' => $validated['address'],
        'previous_job' => $validated['previous_job'] ?? null,
        'education_level' => $validated['education_level'] ?? null,
        'phone' => $validated['phone'] ?? null,
                     ]);
     $staffMosque = Staff_mosque::where('staff_id', $staff->id)->first();
     if ($staffMosque) {
        $staffMosque->update([
            'mosque_id' => $validated['mosque_id'],
            'role' =>$validated['role'],
        ]);
     }
     return $this->sendResponse(new StaffResource($staff), "Successfully retrieved all mosques.");
    }
    public function archiveMosqueManager($id)
    {
     $staff = Staff::findOrFail($id);
     $staffMosque = Staff_mosque::where('staff_id', $staff->id)->first();
     if ($staffMosque) {
        $staffMosque->delete();
     }
      $staff->delete();

     return $this->sendResponse(null, "Done archived successfully");
    }
    public function index1()
    {
    $mosques = Staff::all();
    Staff_mosque::all();
    //return $this->sendResponse($mosques ,"Successfully retrieved all mosques.");
    return $this->sendResponse(StaffResource::collection($mosques), "Successfully retrieved all mosques.");

    }
    public function restoreMosqueManager($id)
    {
  
    $staff = Staff::onlyTrashed()->findOrFail($id);
    $staff->restore(); 
    $staffMosque = Staff_mosque::onlyTrashed()->where('staff_id', $id)->first();
    if ($staffMosque) {
        $staffMosque->restore();
    }

    return $this->sendResponse(new StaffResource($staff), "Manager restored successfully");
    }
    public function archivedMosqueManagers()
    { 
     $archivedStaff = Staff::onlyTrashed()->with(['mosque_staff' => function ($query) {
      $query->withTrashed(); 
       }])->get();

     return $this->sendResponse( StaffResource::collection($archivedStaff),"Archived staff retrieved successfully"
      );
    }
    public function getMosqueStaff($mosqueId)
    {
        $staffMosques = Staff_mosque::with('staff')
            ->where('mosque_id', $mosqueId)
            ->orderBy('role', 'asc')
            ->get();
    
            return $this->sendResponse(
                ManagmentResource::collection($staffMosques),
                "Mosque staff with roles retrieved successfully"
            );
    }
    

}
