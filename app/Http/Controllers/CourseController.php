<?php

namespace App\Http\Controllers;
use App\Models\Staff;
use App\Models\schedules;
use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;

class CourseController extends BaseController
{

public function addCourse(CourseRequest $request)
{
    $userPhone = auth()->user()->mynumber;
    $staff = Staff::where('phone', $userPhone)->first();

    if (!$staff) {
        return response()->json(['message' => 'لا يوجد مشرف مطابق لهذا المستخدم'], 404);
    }
    $schedule = schedules::where('staff_id', $staff->id)->first();

    if (!$schedule) {
        return response()->json(['message' => 'لا يوجد دوام مرتبط بهذا المشرف'], 404);
    }
    $validated = $request->validated();
    $course = Course::create([
        'schedule_id' => $schedule->id,
        'title' => $validated['title'],
        'nameTeacher'=>$validated['nameTeacher'],
        'description' => $validated['description'] ?? null,
        'days'=>$validated['days'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
    ]);
    return $this->sendResponse($course,'تمت إضافة الدورة العلمية بنجاح', 201);
}
public function update(CourseRequest $request, $id)
{
    $course = Course::find($id);

    if (!$course) {
        return $this->sendError('الدورة غير موجودة', 404);
    }

    $validated = $request->validated();

    $course->update([
        'title' => $validated['title'],
        'nameTeacher' => $validated['nameTeacher'],
        'description' => $validated['description'] ?? null,
        'days' => $validated['days'],
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
    ]);

    return $this->sendResponse($course, 'تم تحديث الدورة بنجاح');
}
public function show($id)
{
    $course = Course::with('schedule.staff')->find($id);

    if (!$course) {
        return $this->sendError('الدورة غير موجودة', 404);
    }

    return $this->sendResponse($course, 'تم جلب الدورة بنجاح');
}
public function myCourses()
{
    $userPhone = auth()->user()->mynumber;
    $staff = Staff::where('phone', $userPhone)->first();

    if (!$staff) {
        return $this->sendError('لا يوجد مشرف مطابق لهذا المستخدم', 404);
    }

    $scheduleIds = schedules::where('staff_id', $staff->id)->pluck('id');

    $courses = Course::whereIn('schedule_id', $scheduleIds)->get();

    return $this->sendResponse($courses, 'تم جلب جميع الدورات الخاصة بالمشرف');
}
public function destroy($id)
{
    $course = Course::find($id);

    if (!$course) {
        return $this->sendError('الدورة غير موجودة', 404);
    }

    $course->delete();

    return $this->sendResponse(null, 'تم حذف الدورة بنجاح');
}
public function getCoursesBySchedule($scheduleId)
{
    $courses = Course::where('schedule_id', $scheduleId)->get();

    if ($courses->isEmpty()) {
        return $this->sendError('لا توجد دورات لهذا الدوام', 404);
    }

    return $this->sendResponse($courses, 'تم جلب جميع الدورات المرتبطة بالدوام');
}


}
