<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Resources\RequectResource;
use App\Http\Resources\complaintResource;
use App\Models\Complaint;

class RequestController extends BaseController
{
    public function store(StoreRequestRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $req = ModelsRequest::create($data);
        $req->refresh();
        return $this->sendResponse(new RequectResource($req), 'تم إرسال الطلب بنجاح', 201);
    }
    public function myRequests()
    {
        $requests = ModelsRequest::where('user_id', Auth::id())->latest()->get();
        return $this->sendResponse($requests,'تم عرض طلباتي بنجاح', 201);
    }
    public function index()
    {
        $requests = ModelsRequest::with('user')->latest()->get();
        return $this->sendResponse($requests,'تم عرض الطلبات بنجاح', 201);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:مقبول,مرفوض'
        ]);

        $req = ModelsRequest::findOrFail($id);
        $req->status = $request->status;
        $req->save();

        return $this->sendResponse('تم تحديث الطلب بنجاح', 201);
    }
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'progress' => 'required|in:جاري التنفيذ,تم التنفيذ,ملغي'
        ]);

        $req = ModelsRequest::findOrFail($id);
        $req->progress = $request->progress;
        $req->save();
       return $this->sendResponse('تم تحديث الطلب بنجاح', 201);

    }
    public function addcomplaint(StoreComplaintRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $com = Complaint::create($data);
        $com->refresh();
        return $this->sendResponse(new complaintResource($com), 'تم إرسال الطلب بنجاح', 201);
    }
    public function myComplaints()
   {
    $com = Complaint::where('user_id', Auth::id())->latest()->get();
        return $this->sendResponse($com,'تم عرض شكواي بنجاح', 201);
   }
   public function veiwComplaint()
   {
       $requests = Complaint::with('user')->latest()->get();
       return $this->sendResponse($requests,'تم عرض الشكاوي بنجاح', 201);
   }
   public function updateStatusComplaint(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:مفتوحة,قيد المعالجة,تم الحل,مرفوضة',
    ]);

    $req = Complaint::findOrFail($id);
    $req->status = $request->status;
    $req->save();
   return $this->sendResponse('تم تحديث الشكوى بنجاح', 201);
}

}
