<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreMosqueManagerRequest;
use App\Http\Requests\UpdateMosqueManagerRequest;
use App\Http\Requests\TeacherRequest;
use App\Http\Resources\StaffResource;
use App\Http\Resources\ManagmentResource;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Staff_mosque;
use App\Models\schedules;
use App\Models\Mosque;
use Illuminate\Support\Facades\DB;
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
    
    public function addTeacherBySupervisor(TeacherRequest $request)
    {
        $request->validated();
    
        $userPhone = auth()->user()->mynumber;
        $supervisor = Staff::where('phone', $userPhone)->first();
    
        if (!$supervisor) {
            return response()->json(['message' => 'المستخدم الحالي ليس مشرفاً'], 403);
        }
        $mosqueId = DB::table('staff_mosques')
            ->where('staff_id', $supervisor->id)
            ->value('mosque_id');
    
        if (!$mosqueId) {
            return response()->json(['message' => 'لم يتم ربط المشرف بأي مسجد'], 403);
        }
        $teacher = Staff::create([
            'full_name' => $request->full_name,
            'mother_name' => $request->mother_name,
            'birth_date' => $request->birth_date,
            'national_id' => $request->national_id,
            'address' => $request->address,
            'previous_job' => $request->previous_job,
            'education_level' => $request->education_level,
            'phone' => $request->phone,
        ]);
        $teacher->mosques()->attach($mosqueId, ['role' => 'أستاذ']);
    
        return response()->json(['message' => 'تمت إضافة الأستاذ بنجاح وربطه بالمسجد']);
    }
    public function getMyTeachers()
{
    $userPhone = auth()->user()->mynumber;
    $supervisor = Staff::where('phone', $userPhone)->first();

    if (!$supervisor) {
        return response()->json(['message' => 'المستخدم الحالي ليس مشرفاً'], 403);
    }

    $mosqueId = DB::table('staff_mosques')
        ->where('staff_id', $supervisor->id)
        ->value('mosque_id');

    if (!$mosqueId) {
        return response()->json(['message' => 'لم يتم ربط المشرف بأي مسجد'], 403);
    }

    $teachers = DB::table('staff')
        ->join('staff_mosques', 'staff.id', '=', 'staff_mosques.staff_id')
        ->where('staff_mosques.mosque_id', $mosqueId)
        ->where('staff_mosques.role', 'أستاذ')
        ->select('staff.*', 'staff_mosques.role')
        ->get();
        return $this->sendResponse($teachers, 'تم جلب جميع الاساتذة  ');
}
public function showTeacher($id)
{
    $userPhone = auth()->user()->mynumber;
    $supervisor = Staff::where('phone', $userPhone)->first();

    if (!$supervisor) {
        return response()->json(['message' => 'المستخدم الحالي ليس مشرفاً'], 403);
    }

    $mosqueId = DB::table('staff_mosques')
        ->where('staff_id', $supervisor->id)
        ->value('mosque_id');

    if (!$mosqueId) {
        return response()->json(['message' => 'لم يتم ربط المشرف بأي مسجد'], 403);
    }

    $teacher = DB::table('staff')
        ->join('staff_mosques', 'staff.id', '=', 'staff_mosques.staff_id')
        ->where('staff.id', $id)
        ->where('staff_mosques.mosque_id', $mosqueId)
        ->where('staff_mosques.role', 'أستاذ')
        ->select('staff.*', 'staff_mosques.role')
        ->first();

    if (!$teacher) {
        return response()->json(['message' => 'الأستاذ غير موجود أو غير مرتبط بمسجدك'], 404);
    }
    return $this->sendResponse($teacher, 'تم جلب معلومات الاستاذ بنجاح  ');
}
public function updateTeacher(Request $request, $id)
{
    $request->validate([
        'full_name' => 'sometimes|string',
        'mother_name' => 'sometimes|string',
        'birth_date' => 'sometimes|date',
        'national_id' => 'sometimes|string',
        'address' => 'sometimes|string',
        'previous_job' => 'sometimes|string',
        'education_level' => 'sometimes|string',
        'phone' => 'sometimes|string',
    ]);

    $userPhone = auth()->user()->mynumber;
    $supervisor = Staff::where('phone', $userPhone)->first();

    $mosqueId = DB::table('staff_mosques')
        ->where('staff_id', $supervisor->id)
        ->value('mosque_id');

    $teacher = DB::table('staff_mosques')
        ->where('staff_id', $id)
        ->where('mosque_id', $mosqueId)
        ->where('role', 'أستاذ')
        ->exists();

    if (!$teacher) {
        return response()->json(['message' => 'لا يمكنك تعديل هذا الأستاذ'], 403);
    }

    $staff = Staff::find($id);
    $staff->update($request->all());
    return $this->sendResponse($staff, 'تم تعديل معلومات الاستاذ بنجاح  ');
}

public function deleteTeacher($id)
{
    $userPhone = auth()->user()->mynumber;
    $supervisor = Staff::where('phone', $userPhone)->first();

    if (!$supervisor) {
        return response()->json(['message' => 'المستخدم الحالي ليس مشرفاً'], 403);
    }
    $mosqueId = $supervisor->mosque_staff()->value('mosque_id');

    if (!$mosqueId) {
        return response()->json(['message' => 'لم يتم ربط المشرف بأي مسجد'], 403);
    }
    $relation = Staff_mosque::where('staff_id', $id)
        ->where('mosque_id', $mosqueId)
        ->where('role', 'أستاذ')
        ->first();

    if (!$relation) {
        return response()->json(['message' => 'لا يمكنك حذف هذا الأستاذ أو لم يتم العثور على علاقة.'], 403);
    }

    $relation->forceDelete(); 
    $remainingRelations = Staff_mosque::where('staff_id', $id)->count();
    if ($remainingRelations === 0) {
        $teacher = Staff::find($id);
        if ($teacher) {
            $teacher->forceDelete(); 
            return response()->json(['message' => 'تم حذف الأستاذ نهائيًا من المسجد ومن قاعدة البيانات.']);
        }
    }

    return response()->json(['message' => 'تم حذف الأستاذ من هذا المسجد فقط، ولا يزال مرتبطًا بمساجد أخرى.']);
}
public function getTeachersBySchedule($scheduleId)
{
    $schedule = schedules::with('Staff')->find($scheduleId);

    if (!$schedule) {
        return response()->json(['message' => 'الدوام غير موجود'], 404);
    }

    $supervisor = $schedule->Staff;

    if (!$supervisor) {
        return response()->json(['message' => 'لا يوجد مشرف مرتبط بهذا الدوام'], 404);
    }

    // جلب المسجد المرتبط بالمشرف
    $mosqueId = $supervisor->mosque_staff()->value('mosque_id');

    if (!$mosqueId) {
        return response()->json(['message' => 'المشرف غير مرتبط بأي مسجد'], 403);
    }

    // جلب جميع الأشخاص المرتبطين بنفس المسجد باستثناء المشرف نفسه
    $staff = Staff::whereHas('mosque_staff', function ($query) use ($mosqueId) {
        $query->where('mosque_id', $mosqueId);
    })
    ->where('id', '!=', $supervisor->id)
    ->with(['mosque_staff' => function($q) use ($mosqueId) {
        $q->where('mosque_id', $mosqueId);
    }])
    ->get();

    // تجهيز البيانات مع الأدوار
    $staffWithRoles = $staff->map(function($person) {
        $person->roles = $person->mosque_staff->pluck('role');
        unset($person->mosque_staff);
        return $person;
    });

    return response()->json([
        'schedule' => $schedule->name,
        'supervisor' => $supervisor,
        'staff' => $staffWithRoles
    ]);
}




}
