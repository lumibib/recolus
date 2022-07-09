<?php

namespace App\Http\Controllers;

use Akaunting\Apexcharts\Chart;
use App\Models\Record;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
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
     * View site data.
     *
     * @param  Request  $request
     * @return View
     */
    public function view(Request $request, $site)
    {
        $site = Site::where('uuid', $site)->first();

        if (empty($site)) {
            abort(404, 'Site web analysis not found.');
        }

        /* Hide not public site and for not connected user */
        if (! $site->public && ! Auth::check()) {
            abort(403, 'Web analysis of the site not publicly accessible.');
        }

        $period = $request->get('period', 'this_month');
        $variable = $request->get('variable');

        $this->site = $site;
        $this->period = $period;
        $this->variable = $variable;

        return view('site', [
            'site' => $site,
            'period' => $period,
            'variable' => $variable,
            'periods' => $this->periods(),
            'stats' => $this->stats(),
            'pages' => $this->pages(),
            'sources' => $this->sources(),
            'devices' => $this->devices(),
            'browsers' => $this->browsers(),
            'platforms' => $this->platforms(),
            'languages' => $this->languages(),
            'variables' => $this->variables(),
            'countries' => $this->countries(),
            'pagesChart' => $this->pagesChart(),
        ]);
    }

    protected function periods(): array
    {
        return config('recolus.periods');
    }

    protected function stats(): array
    {
        return [
            [
                'key' => 'visitors',
                'name' => 'Visitors',
                'description' => 'Total of each visitor\'s first visit in a single day',
                'unit' => 'visitors',
                'value' => Record::query()
                    ->where('site_id', $this->site->id)
                    ->where('is_first_visit', 1)
                    ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
                    ->count(),
            ],
            [
                'key' => 'page_views',
                'name' => 'Page views',
                'description' => 'Total number of pages viewed by visitors',
                'unit' => 'pages',
                'value' => Record::query()
                    ->where('site_id', $this->site->id)
                    ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
                    ->count(),
            ],
            [
                'key' => 'duration',
                'name' => 'Duration',
                'description' => 'Average time spent on pages by visitors',
                'unit' => 's',
                'value' => Record::query()
                    ->where('site_id', $this->site->id)
                    ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
                    ->avg('duration'),
            ],
        ];
    }

    protected function pages(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select(
                '*',
                'path',
                DB::raw('COUNT(*) AS total'),
                DB::raw('AVG(`duration`) AS avg_duration'),
                DB::raw('AVG(`scroll`) AS avg_scroll'),
                DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage')
            )
            ->groupBy('path')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function sources(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('referrer as source', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->whereNot(function ($query) {
                $query->where('referrer', 'like', '%'.$this->site->domain.'%');
            })
            ->orWhereNull('referrer')
            ->groupBy('source')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function devices(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('device', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function browsers(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('browser', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->groupBy('browser')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function platforms(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('platform', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->groupBy('platform')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function countries(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('country', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->groupBy('country')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function languages(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->select('language', DB::raw('count(*) as total'), DB::raw('COUNT(*) * 100.0 / SUM(COUNT(*)) OVER () AS percentage'))
            ->groupBy('language')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function variables(): Collection
    {
        return Record::query()
            ->where('site_id', $this->site->id)
            ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
            ->whereNotNull('custom_variable')
            ->select('custom_variable as variable', DB::raw('count(*) as total'))
            ->groupBy('variable')
            ->orderBy('total', 'desc')
            ->get();
    }

    protected function pagesChart()
    {
        /* Determine the SELECT query for grouping dates */
        if ($this->period == 'today') {
            $groupQueryType = "CONCAT(CAST(DATE(created_at) AS CHAR), ' ', CAST(HOUR(created_at) AS CHAR), ':00:00') AS date";
        } elseif (str($this->period)->contains('months')) {
            /* Period : week */
            $groupQueryType = 'DATE(created_at + INTERVAL (6 - WEEKDAY(created_at)) DAY) AS date';
        /* Period : month */
            // $groupQueryType = "CAST(LAST_DAY(created_at) AS CHAR) AS date";
        } else {
            $groupQueryType = 'DATE(created_at) AS date';
        }

        $pageViewsQuery = Record::query()
                    ->select([
                        DB::raw($groupQueryType),
                        DB::raw('COUNT(id) AS count'),
                    ])
                    ->where('site_id', $this->site->id)
                    ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get();

        $visitorsQuery = Record::query()
                    ->select([
                        DB::raw($groupQueryType),
                        DB::raw('COUNT(id) AS count'),
                    ])
                    ->where('site_id', $this->site->id)
                    ->where('is_first_visit', 1)
                    ->scopes(['period' => [$this->period], 'variable' => [$this->variable]])
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get();

        $pageViewsData = $pageViewsQuery->map(function ($item, $key) {
            return [$item['date'], $item['count']];
        });
        $visitorsData = $visitorsQuery->map(function ($item, $key) {
            return [$item['date'], $item['count']];
        });

        /* Determine the chart type */
        $chartType = $this->period == 'today' ? 'bar' : 'area';

        $chart = (new Chart)->setType($chartType)
        ->setSubTitle('')
        ->setWidth('100%')
        ->setHeight(500)
        ->setStrokeCurve('smooth')
        ->setXaxisType('datetime')
        ->setYaxisTickAmount(1)
        ->setYaxisLabels([
            '-' => '-',
        ])
        ->setSeries([
            [
                'name' => 'Pages views',
                'data' => $pageViewsData,
            ],
            [
                'name' => 'Visitors',
                'data' => $visitorsData,
            ],
        ]);

        return $chart;
    }

    protected function devicesChart()
    {
        $devicesData = $this->devices()->mapWithKeys(function ($item, $key) {
            return [$item['device'] => $item['total']];
        });

        $chart = (new Chart)->setType('donut')
        ->setSubTitle('')
        ->setWidth('100%')
        ->setHeight(300)
        ->setLabels($devicesData->keys()->all())
        ->setSeries($devicesData->values()->all());

        return $chart;
    }
}
