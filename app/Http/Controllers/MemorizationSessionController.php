<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Schedules;
use App\Models\MemorizationSession;
use App\Http\Requests\MemorizationSessionRequest;
use Illuminate\Http\Request;

class MemorizationSessionController extends BaseController
{
  
    public function store(MemorizationSessionRequest $request)
    {
        $userPhone = auth()->user()->mynumber;
        $staff = Staff::where('phone', $userPhone)->first();

        if (!$staff) return $this->sendError('المستخدم غير موجود كمشرف', 404);

        $schedule = Schedules::where('staff_id', $staff->id)->first();
        if (!$schedule) return $this->sendError('لا يوجد دوام مرتبط بالمشرف', 404);

        $session = MemorizationSession::create([
            'schedule_id' => $schedule->id,
            'name_memorization_sessions' => $request->name_memorization_sessions,
            'teacher_name' => $request->teacher_name,
            'NumberStudent' => $request->NumberStudent,
            'Catogry' => $request->Catogry,
        ]);

        return $this->sendResponse($session, 'تمت إضافة حلقة التحفيظ بنجاح');
    }

   
    public function update(MemorizationSessionRequest $request, $id)
    {
        $session = MemorizationSession::find($id);
        if (!$session) return $this->sendError('الحلقة غير موجودة', 404);

        $session->update($request->validated());

        return $this->sendResponse($session, 'تم تعديل حلقة التحفيظ بنجاح');
    }

  
    public function show($id)
    {
        $session = MemorizationSession::with('schedule.staff')->find($id);
        if (!$session) return $this->sendError('الحلقة غير موجودة', 404);

        return $this->sendResponse($session, 'تم جلب بيانات الحلقة');
    }

    
    public function destroy($id)
    {
        $session = MemorizationSession::find($id);
        if (!$session) return $this->sendError('الحلقة غير موجودة', 404);

        $session->delete();
        return $this->sendResponse(null, 'تم حذف الحلقة بنجاح');
    }

    public function mySessions()
    {
        $userPhone = auth()->user()->mynumber;
        $staff = Staff::where('phone', $userPhone)->first();

        if (!$staff) return $this->sendError('المستخدم غير موجود', 404);

        $scheduleIds = Schedules::where('staff_id', $staff->id)->pluck('id');

        $sessions = MemorizationSession::whereIn('schedule_id', $scheduleIds)->get();

        return $this->sendResponse($sessions, 'تم جلب جميع الحلقات الخاصة بالمشرف');
    }

   
    public function getBySchedule($scheduleId)
    {
        $sessions = MemorizationSession::where('schedule_id', $scheduleId)->get();

        if ($sessions->isEmpty()) {
            return $this->sendError('لا توجد حلقات لهذا الدوام', 404);
        }

        return $this->sendResponse($sessions, 'تم جلب جميع الحلقات المرتبطة بالدوام');
    }
}
