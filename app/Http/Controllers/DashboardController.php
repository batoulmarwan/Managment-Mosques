<?php

namespace App\Http\Controllers;
use App\Models\Mosque;
use App\Models\Request;
use App\Models\Staff_mosque;
use App\Models\RequestModel;
use App\Models\Complaint;

class DashboardController extends BaseController
{
    public function getStats()
    {
        $mosqueCount = Mosque::count();
        $supervisorCount = Staff_mosque::where('role', 'مشرف')->count();
        $requestsCount = Request::count();
        $complaintsCount = Complaint::count();

        return $this->sendResponse([
            'mosques' => $mosqueCount,
            'supervisors' => $supervisorCount,
            'requests' => $requestsCount,
            'complaints' => $complaintsCount,
        ], 'تم جلب الإحصائيات بنجاح');
    }
}
