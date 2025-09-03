<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\schedules;
use App\Http\Requests\StoreScheduleRequest;

class SchedulController extends BaseController
{
    public function store(StoreScheduleRequest $request)
    {
    
        $validated = $request->validated();
        
        $schedule = schedules::create($validated);
    
        return $this->sendResponse($schedule,'تم انشاء الدوام بنجاح', 201);
    }
    public function view(){
        $data=schedules::all();
        return $this->sendResponse($data,'تم عرض اوقام دوامات المساجد بنجاح', 201);
    }
    public function update(StoreScheduleRequest $request,$id){
        $validated = $request->validated();
        $req = schedules::findOrFail($id);
        $req->update([
            'mosque_id' => $validated['mosque_id'],
            'staff_id' => $validated['staff_id'],
            'name' => $validated['name'],
            'type' => $validated['type'],
            'daysOfWeek' => $validated['daysOfWeek'],
            'end_time' => $validated['end_time'] ,
            'start_time' => $validated['start_time'] ,
                         ]);
        $req->save();
        return $this->sendResponse($req,'تم تحديث معلومات الدوام بنجاح', 201);
    }
    public function destroy($id)
    {
        $data = schedules::findOrFail($id);
        $data->delete();
        return $this->sendResponse([], 'تم حذف معلومات الدوام بنجاح');
    }

}