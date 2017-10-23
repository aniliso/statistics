@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('statistics::dashboards.title.statistics') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('statistics::dashboards.title.statistics') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>{!! $statistics['active_visitors'] !!}</h3>

                            <p>{{ trans('statistics::dashboards.title.active_visitors') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{!! $statistics['total_visits'] !!}</h3>

                            <p>{{ trans('statistics::dashboards.title.total_visitors') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-stalker"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{!!  $statistics['average_bounce'] !!}<sup style="font-size: 20px">%</sup></h3>
                            <p>{{ trans('statistics::dashboards.title.bounce_rate') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{!! $statistics['average_time'] !!}</h3>

                            <p>{{ trans('statistics::dashboards.title.page_time') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-timer-outline"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{!! $statistics['average_visits'] !!}</h3>

                            <p>{{ trans('statistics::dashboards.title.average_page') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-arrow-swap"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>{!! $statistics['page_views'] !!}</h3>

                            <p>{{ trans('statistics::dashboards.title.page_views') }}</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-eye"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <!-- LINE CHART -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('statistics::dashboards.title.visitors') }}</h3> ({!! $statistics['start_date'] . ' - ' .$statistics['end_date'] !!})
                    <span class="pull-right"><span class="label label-warning">Geçen Sene</span> <span class="label label-primary">Bu Sene</span></span>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="visitor-chart" style="height: 300px;"></div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_page" data-toggle="tab">{{ trans('statistics::dashboards.title.page') }}</a></li>
                            <li><a href="#tab_keywords" data-toggle="tab">{{ trans('statistics::dashboards.title.keywords') }}</a></li>
                            <li><a href="#tab_entrance" data-toggle="tab">{{ trans('statistics::dashboards.title.entrance') }}</a></li>
                            <li><a href="#tab_exit" data-toggle="tab">{{ trans('statistics::dashboards.title.exit') }}</a></li>
                            <li><a href="#tab_time" data-toggle="tab">{{ trans('statistics::dashboards.title.time') }}</a></li>
                            <li><a href="#tab_source" data-toggle="tab">{{ trans('statistics::dashboards.title.source') }}</a></li>
                            <li><a href="#tab_browser" data-toggle="tab">{{ trans('statistics::dashboards.title.browser') }}</a></li>
                            <li><a href="#tab_os" data-toggle="tab">{{ trans('statistics::dashboards.title.os') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_page">
                                <table class="table analytics">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.page') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.visit') }}</th>
                                    </tr>
                                    @foreach($statistics['pages'] as $p)
                                    <tr>
                                        <td>{{ $p['url'] }}</td>
                                        <td><span class="badge bg-red">{{ $p['pageViews'] }}</span></td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_keywords">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.keywords') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.session') }}</th>
                                    </tr>
                                    @foreach($statistics['keywords'] as $p)
                                        <tr>
                                            <td>{{ $p['keyword'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['sessions'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_entrance">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.page') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.entrance') }}</th>
                                    </tr>
                                    @foreach($statistics['landings'] as $p)
                                        <tr>
                                            <td>{{ $p['path'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['visits'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_exit">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.page') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.exit') }}</th>
                                    </tr>
                                    @foreach($statistics['exits'] as $p)
                                        <tr>
                                            <td>{{ $p['path'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['visits'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_time">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.page') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.time') }}</th>
                                    </tr>
                                    @foreach($statistics['times'] as $p)
                                        <tr>
                                            <td>{{ $p['path'] }}</td>
                                            <td><span class="badge bg-red">{{ formatMilliseconds($p['time']) }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_source">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.source') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.visit') }}</th>
                                    </tr>
                                    @foreach($statistics['sources'] as $p)
                                        <tr>
                                            <td>{{ $p['path'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['visits'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_browser">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.browser') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.visit') }}</th>
                                    </tr>
                                    @foreach($statistics['browsers'] as $p)
                                        <tr>
                                            <td>{{ $p['browser'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['visits'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_os">
                                <table class="table">
                                    <tbody><tr>
                                        <th>{{ trans('statistics::dashboards.title.os') }}</th>
                                        <th>{{ trans('statistics::dashboards.title.visit') }}</th>
                                    </tr>
                                    @foreach($statistics['ops'] as $p)
                                        <tr>
                                            <td>{{ $p['os'] }}</td>
                                            <td><span class="badge bg-red">{{ $p['visits'] }}</span></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                </div>
                <!-- /.col -->

                <div class="col-md-6 col-xs-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_country" data-toggle="tab">{{ trans('statistics::dashboards.title.country') }}</a></li>
                            <li><a href="#tab_countries" data-toggle="tab">{{ trans('statistics::dashboards.title.countries') }}</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_country">
                                <div class="box box-solid bg-blue-special">
                                    <div class="box-body">
                                        <div id="region-map"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_countries">
                                <div class="box box-solid bg-blue-special">
                                    <div class="box-body">
                                        <div id="world-map"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- /.col -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
{!! Theme::style('vendor/morris/morris.css') !!}
<style>
    table.analytics th:last-child {
        width: 20%;
    }
</style>
@stop

@section('scripts')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    {!! Theme::script('vendor/morris/raphael-min.js') !!}
    {!! Theme::script('vendor/morris/morris.min.js') !!}

    <script type="text/javascript">
        // LINE CHART
        var line = new Morris.Line({
            element: 'visitor-chart',
            resize: true,
            data: {!! $statistics['visitors'] !!},
            xkey: 'date',
            ykeys: ['visits', 'last_visits'],
            labels: ['{{ trans('statistics::dashboards.title.visitor') }}', '{{ trans('statistics::dashboards.title.last_year') }} {{ trans('statistics::dashboards.title.visitor') }}'],
            lineColors: ['#3c8dbc', 'orange'],
            hideHover: 'auto'
        });

        google.load("visualization", "1", {packages:["geochart"]});
        google.setOnLoadCallback(drawLocalRegionsMap);
        google.setOnLoadCallback(drawRegionsMap);

        function drawLocalRegionsMap(){
            var data = new google.visualization.DataTable();
            data.addColumn('string', '{{ trans('statistics::dashboards.title.region') }}');
            data.addColumn('number', '{{ trans('statistics::dashboards.title.visitor') }}');
            data.addRows({!! $statistics['regions'] !!});
            var options = {
                colorAxis: {colors: ['#e7711c', '#4374e0']},
                backgroundColor: '#55a9bc',
                legend:  {textStyle: {color: '#000', fontName: 'Source Sans Pro'}},
                displayMode: 'markers',
                region: '{{ env('ANALYTICS_COUNTRY_CODE', 'TR') }}',
                resolution: 'provinces'
            };
            var chart = new google.visualization.GeoChart(document.getElementById('region-map'));
            chart.draw(data, options);
        }

        function drawRegionsMap() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', '{{ trans('statistics::dashboards.title.country') }}');
            data.addColumn('number', '{{ trans('statistics::dashboards.title.visitor') }}');
            data.addRows({!! $statistics['countries'] !!});
            var options = {
                colors: ['#c8e0ed','#24536e'],
                backgroundColor: '#f9f9f9',
                datalessRegionColor: '#e5e5e5',
                legend:  {textStyle: {fontName: 'Source Sans Pro'}},
                width: $('#world-map').width()
            };
            var chart = new google.visualization.GeoChart(document.getElementById('world-map'));
            chart.draw(data, options);
        }
    </script>
@stop