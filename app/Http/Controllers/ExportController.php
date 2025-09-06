<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\DecisionCreated;
use App\Models\Decision;
class ExportController extends BaseController
{
    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function issueDecision(Request $request)
{
         $request->validate([
            'type_decision' => 'required|string',
            'full_name' => 'required',
            'mosque_name' => 'required',
            'date_decision' => 'required',
           ]);
         $decision = Decision::create([
          'type_decision' => $request->type_decision,
          'full_name' => $request->full_name,
          'mosque_name' => $request->mosque_name,
          'date_decision' => $request->date_decision,
           ]);
           broadcast(new DecisionCreated($decision));
           return $this->sendResponse($decision, "تم اصدار القرار وبثه بنجاح");
}
public function getLatestDecision(Request $request)
{
    $decision = Decision::latest()->first();
    return $this->sendResponse($decision, "آخر قرار تم إصداره");
}

}
