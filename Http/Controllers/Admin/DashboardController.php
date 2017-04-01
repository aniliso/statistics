<?php

namespace Modules\Statistics\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Statistics\Services\Statistics;

class DashboardController extends AdminBaseController
{
    private $statistics;

    public function __construct(Statistics $statistics)
    {
        parent::__construct();
        $this->statistics = $statistics;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statistics = [
          'active_visitors' => $this->statistics->getActiveVisitors(),
          'visitors' => $this->statistics->getCached('getDailyVisits'),
          'total_visits' => $this->statistics->getCached('getTotalVisits'),
          'average_time' => $this->statistics->getCached('getAverageTime'),
          'average_bounce' => $this->statistics->getCached('getBounceRate'),
          'average_visits' => $this->statistics->getCached('getAverageVisits'),
          'pages' => $this->statistics->getCached('getMostVisitedPages'),
          'browsers' => $this->statistics->getCached('getBrowsers'),
          'keywords' => $this->statistics->getCached('getTopKeywords'),
          'landings' => $this->statistics->getCached('getLandings'),
          'exits' => $this->statistics->getCached('getExits'),
          'times' => $this->statistics->getCached('getTimeOnPages'),
          'ops' => $this->statistics->getCached('getOperatingSystems'),
          'countries' => $this->statistics->getCached('getCountries'),
          'sources' => $this->statistics->getCached('getSources'),
          'regions' => $this->statistics->getCached('getRegions'),
          'page_views' => $this->statistics->getCached('getPageViews'),
          'start_date' => $this->statistics->getStartDate(),
          'end_date' => $this->statistics->getEndDate()
        ];
        return view('statistics::admin.dashboards.index', compact('statistics'));
    }
}
