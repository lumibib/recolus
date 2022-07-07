<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Site;
use App\Models\Record;
use Akaunting\Apexcharts\Chart;

class ViewsController extends Controller
{

    /**
     * Variables.
     *
     * @var string
     */
    protected $period;
    protected $site;
    protected $variable;

    /**
     * Welcome view.
     *
     * @param  Request $request
     * @return View
     */
    public function welcome(Request $request)
    {
        if (Auth::check()) {
            $sites = Site::all();
        } else {
            $sites = Site::where('public_list', 1)->get();
        }

        $miniSitesCharts = [];
        foreach ($sites as $site) {
            $miniSitesCharts[] = $this->miniPagesSiteChart($site->id);
        };

        return view('welcome', [
            'sites' => $sites,
            'miniSitesCharts' => $miniSitesCharts,
        ]);
    }

    protected function miniPagesSiteChart($siteId)
    {

        $pageViewsQuery = Record::query()
                    ->select([
                        DB::raw('DATE(created_at + INTERVAL (6 - WEEKDAY(created_at)) DAY) AS date'),
                        DB::raw('COUNT(id) AS count'),
                    ])
                    ->where('site_id', $siteId)
                    ->scopes(['period' => '12_months'])
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get();

        $pageViewsData = $pageViewsQuery->map(function ($item, $key) {
            return [$item['date'], $item['count']];
        });

        $chart = (new Chart)->setType('area')
        ->setSubTitle('Last year')
        ->setWidth('100%')
        ->setHeight(100)
        ->setXAxisLabels(['show' => false])
        ->setYAxisLabels(['show' => false])
        ->setStrokeCurve('smooth')
        ->setXaxisType('datetime')
        ->setGridShow(false)
        ->setMarkersSize(0)
        ->setDataLabelsEnabled(false)
        ->setTooltipEnabled(false)
        ->setLegendShow(false)
        ->setSeries([['name' => 'Pages views', 'data' => $pageViewsData]]);

        return $chart;
    }

    /**
     * Site settings view.
     *
     * @param  Request $request
     * @return View
     */
    public function siteSettings(Request $request, $site)
    {
        $site = Site::where('uuid', $site)->first();

        if (empty($site)) {
            abort(404);
        }


        return view('site-settings', [
            'site' => $site,
        ]);
    }

    /**
     * Site settings view.
     *
     * @param  Request $request
     * @return View
     */
    public function siteUpdate(Request $request, $site)
    {
        $site = Site::find($site);

        if (empty($site)) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|max:256',
            'domain' => 'required|max:256',
            'public' => 'integer|min:1|nullable',
            'public_list' => 'integer|min:1|nullable',
            'domain_whitelist' => "nullable|regex:/^(?'big'([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,})(,(?&big))*$/i",
            'ignore_paths' => "nullable|regex:/^(?'big'\/[a-zA-Z0-9-:\/#?*]+)(,(?&big))*$/i",
        ]);

        $site->name = $request->input('name');
        $site->domain = $request->input('domain');
        $site->public = $request->has('public') ? 1 : 0 ;
        $site->public_list = $request->has('public_list') ? 1 : 0 ;
        $site->domain_whitelist = $request->input('domain_whitelist');
        $site->ignore_paths = $request->input('ignore_paths');
        $site->save();

        return redirect()->route('site.settings', ['site' => $site->uuid]);
    }
}
