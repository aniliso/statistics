<?php namespace Modules\Statistics\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Analytics;
use Spatie\Analytics\Period;

class Statistics
{
    /**
     * @var int
     */
    private $period;
    /**
     * @var int
     */
    private $limit;
    /**
     * @var static
     */
    private $start;
    /**
     * @var static
     */
    private $end;
    /**
     * @var array
     */
    private $averages;
    /**
     * @var mixed|string
     */
    private $country;
    /**
     * @var int
     */
    private $cachePeriod = 3600;

    /**
     * Statistics constructor.
     */
    public function __construct()
    {
        setlocale(LC_TIME, locale().'-'.strtoupper(locale()));
        Carbon::setLocale(locale());
        $this->period = 30;
        $this->limit = 16;
        $this->end = Carbon::today();
        $this->start = Carbon::today()->subDays($this->period);
        $this->averages = $this->getAverages();
        $this->country = env('ANALYTICS_COUNTRY');
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return Carbon::parse($this->start)->formatLocalized('%d %B %Y');
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return Carbon::parse($this->end)->formatLocalized('%d %B %Y');
    }

    /**
     * @param $method
     * @return mixed
     */
    public function getCached($method) {
        if(!$data = \Cache::get('statistics.'.$method)) {
            $data = call_user_func(array($this, $method));
            \Cache::add('statistics.'.$method, $data, $this->cachePeriod);
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDailyVisits()
    {
        $lastYear = $this->setStartDate(Carbon::parse('last year')->subDays($this->period))
                         ->setEndDate(Carbon::parse('last year'))
                         ->query(['dimensions' => 'ga:date']);

        $visits = [];

        foreach ($lastYear as $k => $v) {
            $visits[$k]['last_date'] = Carbon::parse($v['0'])->format('Y-m-d');
            $visits[$k]['last_visits'] = $v['1'];
        }

        $options = [
            'dimensions' => 'ga:date'
        ];
        $array = $this->setStartDate(Carbon::today()->subDays($this->period))
                      ->setEndDate(Carbon::today())->query($options);

        foreach($array as $k => $v)
        {
            $visits[$k]['date']   = Carbon::parse($v['0'])->format('Y-m-d');
            $visits[$k]['visits'] = $v['1'];
        }

        return json_encode($visits);
    }

    /**
     * @param \DateTime $startDate
     * @return $this
     */
    private function setStartDate(\DateTime $startDate)
    {
        $this->start = $startDate;
        return $this;
    }

    /**
     * @param \DateTime $endDate
     * @return $this
     */
    private function setEndDate(\DateTime $endDate)
    {
        $this->end = $endDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getActiveVisitors()
    {
        $options = [
            'dimensions' => 'ga:userType',
        ];
        return $this->query($options)[0][1];
    }

    /**
     * @return mixed
     */
    public function getTotalVisits()
    {
        $options = [
            'dimensions' => 'ga:year',
        ];
        return $this->query($options)[0][1];
    }

    /**
     * @return string
     */
    public function getAverageTime()
    {
        return $this->formatMilliseconds($this->averages['time']);
    }

    /**
     * @return mixed
     */
    public function getAverageVisits()
    {
        return $this->averages['visit'];
    }

    /**
     * @return mixed
     */
    public function getBounceRate()
    {
        return $this->averages['bounce'];
    }

    /**
     * @return \Spatie\Analytics\Collection
     */
    public function getMostVisitedPages()
    {
        return Analytics::fetchMostVisitedPages(Period::days(30));
    }

    /**
     * @return \Spatie\Analytics\Collection
     */
    public function getTopKeywords()
    {
        $options = [
            'dimensions' => 'ga:keyword', 'sort' => '-ga:sessions', 'max-results' => 20, 'filters' => 'ga:keyword!=(not set);ga:keyword!=(not provided)'
        ];
        $response = $this->query($options, 'ga:sessions');

        return collect($response['rows'] ?? [])
            ->map(function (array $pageRow) {
                return [
                    'keyword'  => $pageRow[0],
                    'sessions' => $pageRow[1]
                ];
            });
    }

    /**
     * @return \Spatie\Analytics\Collection
     */
    public function getTopReferrers()
    {
        return Analytics::getTopReferrers($this->period, $this->limit);
    }

    /**
     * @return mixed
     */
    public function getPageViews()
    {
        $options = [
          'dimensions' => 'ga:pagePath'
        ];

        $data = $this->query($options, 'ga:pageviews');
        $collection = $this->makeCollection($data, ['0' => 'path', '1' => 'visits'], 1);
        return $collection->sum('visits');
    }

    /**
     * @return string
     */
    public function getRegions()
    {
        $options = [
            'dimensions' => 'ga:country, ga:region',
            'sort' => '-ga:visits',
            'filters' => 'ga:country==Turkey'
        ];
        $array = $this->query($options);
        $visits = [];
        if(count($array))
        {
            foreach($array as $k => $v)
            {
                $visits[$k] = [str_replace(" Province", "", $v[1]), (int) $v[2]];
            }
        }
        return json_encode($visits);
    }

    /**
     * @return Collection
     */
    public function getLandings()
    {
        $options = [
            'dimensions' => 'ga:landingPagePath',
            'sort' => '-ga:entrances',
            'max-results' => $this->limit
        ];
        $data = $this->query($options,'ga:entrances');
        return $this->makeCollection($data, ['0' => 'path', '1' => 'visits']);
    }

    /**
     * @return Collection
     */
    public function getExits()
    {
        $options = [
            'dimensions' => 'ga:exitPagePath',
            'sort' => '-ga:exits',
            'max-results' => $this->limit
        ];
        $data = $this->query($options,'ga:exits');
        return $this->makeCollection($data, ['0' => 'path', '1' => 'visits']);
    }

    /**
     * @return Collection
     */
    public function getTimeOnPages()
    {
        $options = [
            'dimensions' => 'ga:pagePath',
            'sort' => '-ga:timeOnPage',
            'max-results' => $this->limit
        ];
        $data = $this->query($options, 'ga:timeOnPage');
        return $this->makeCollection($data, ['0' => 'path', '1' => 'time']);
    }

    /**
     * @return Collection
     */
    public function getOperatingSystems()
    {
        $options = [
            'dimensions' => 'ga:operatingSystem',
            'sort' => '-ga:visits',
            'max-results' => $this->limit
        ];
        $data = $this->query($options);
        return $this->makeCollection($data, ['0' => 'os', '1' => 'visits']);
    }

    /**
     * @return string
     */
    public function getCountries()
    {
        $options = [
            'dimensions' => 'ga:country',
            'sort' => '-ga:visits'
        ];
        $array = $this->query($options);
        $visits = [];
        if(count($array))
        {
            foreach($array as $k => $v)
            {
                $visits[$k] = [$v[0], (int) $v[1]];
            }
        }
        return json_encode($visits);
    }

    /**
     * @return Collection
     */
    public function getSources()
    {
        $options = [
            'dimensions' => 'ga:source, ga:medium',
            'sort' => '-ga:visits',
            'max-results' => $this->limit
        ];
        $data = $this->query($options);
        return $this->makeCollection($data, ['0' => 'path', '1' => 'visits'],2);
    }

    /**
     * @return Collection
     */
    public function getBrowsers()
    {
        $options = [
            'dimensions' => 'ga:browser',
            'sort' => '-ga:visits',
            'max-results' => $this->limit
        ];
        $data = $this->query($options);
        return $this->makeCollection($data, ['0' => 'browser', '1' => 'visits']);
    }

    /**
     * @param $seconds
     * @return string
     */
    private function formatMilliseconds($seconds)
    {
        $hours = 0;
        $milliseconds = str_replace( "0.", '', $seconds - floor( $seconds ) );
        if ( $seconds > 3600 )
        {
            $hours = floor( $seconds / 3600 );
        }
        $seconds = $seconds % 3600;
        return str_pad( $hours, 2, '0', STR_PAD_LEFT )
        . gmdate( ':i:s', $seconds )
        . ($milliseconds ? ".$milliseconds" : '');
    }

    /**
     * @return array
     */
    private function getAverages()
    {
        $options = [
            'dimensions' => 'ga:pagePath'
        ];
        $array = $this->query($options,'ga:avgTimeOnPage , ga:bounceRate, ga:pageviewsPerSession');
        $count = count($array);
        $average = ['time' => 0, 'bounce' => 0, 'visit' => 0];
        if(count($array))
        {
            foreach($array as $v)
            {
                $average['time']   += $v['1'];
                $average['bounce'] += $v['2'];
                $average['visit']  += $v['3'];
            }
            $average['time']   = ($average['time'] ? floor($average['time'] / $count) : 0);
            $average['bounce'] = ($average['bounce'] ? round($average['bounce'] / $count, 2) : 0);
            $average['visit']  = ($average['visit'] ? round($average['visit'] / $count, 2) : 0);
        }
        return $average;
    }

    /**
     * @param array $options
     * @param string $metrics
     * @return mixed
     */
    private function query($options=[], $metrics='ga:visits')
    {
        $period = Period::create(
            $this->start,
            $this->end
        );
        return Analytics::performQuery($period, $metrics, $options);
    }

    /**
     * @param $data
     * @param $fields
     * @param int $offset
     * @return Collection
     */
    private function makeCollection($data, $fields, $offset=1)
    {
        if (is_null($data))
        {
            return new Collection([]);
        }
        else
        {
            foreach ($data as $pageRow)
            {
                $keywordData[] = [$fields[0] => $pageRow[0], $fields[1] => $pageRow[$offset]];
            }
            return new Collection($keywordData);
        }
    }

    /**
     *
     */
    public function cacheClear()
    {
        return \Cache::flush();
    }
}