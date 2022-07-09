<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Site;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class RecordController extends Controller
{
    /**
     * Collect record from script HTTP POST request.
     *
     * @param  Request  $request
     * @return JSON
     */
    public function collect(Request $request)
    {

        /**
         * Declare variable that are used more than once
         */
        $siteId = data_get($request, 'siteId');
        $ts = time();
        $userAgent = data_get($request, 'userAgent');
        $path = data_get($request, 'path');
        $url = data_get($request, 'url');

        /**
         * Create a kind of anonymous session of the visitor.
         * Hash composed of : siteId, userAgent and current date.
         * The salt change every day.
         */
        $nowDate = now()->toDateString();
        $salt = md5($nowDate);
        $anonymousId = hash('sha256', $siteId.$userAgent.$salt);

        /**
         * Check if site id exists.
         */
        $site = Site::where('uuid', $siteId)->first();
        if (empty($site)) {
            return response()->json([
                'code' => '404',
                'status' => 'site_not_found',
                'message' => 'Record not saved. Site id not found.',
            ], 404);
        }

        /**
         * Check if this page has already been viewed by the user in the last 1 minute.
         * Based on anonymousId.
         */
        $lastCloseRecord = Record::where('anonymous_id', $anonymousId)->where('path', $path)->whereBetween('created_at', [now()->subMinutes(1), now()])->orderBy('created_at', 'desc')->first();
        if (isset($lastCloseRecord)) {
            return response()->json([
                'code' => '200',
                'status' => 'already_viewed',
                'message' => 'Record not saved. Already viewed in the last 1 minute.',
            ], 200);
        }

        /**
         * Check if record is unique visit.
         * Check if visitor already have visited this site based on anonymousId.
         */
        $lastVisitedRecord = Record::where('anonymous_id', $anonymousId)->first();
        if (isset($lastVisitedRecord)) {
            $is_first_visit = false;
        } else {
            $is_first_visit = true;
        }

        /**
         * Determine country.
         */
        $timeZoneObject = timezone_open(data_get($request, 'timezone'));
        $country = data_get(timezone_location_get($timeZoneObject), 'country_code');

        /**
         * Determines os, browser and device.
         */
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $isDesktop = $agent->isDesktop();
        $isTablet = $agent->isTablet();
        $isMobile = $agent->isMobile();
        $isRobot = $agent->isRobot();
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $device = ($isDesktop ? 'desktop' : ($isTablet ? 'tablet' : ($isMobile ? 'mobile' : null)));

        /**
         * Check if UA is a crawler or a bot.
         */
        if ($isRobot) {
            return response()->json([
                'code' => '200',
                'status' => 'bot',
                'message' => 'Record not saved. User Agent is a bot.',
            ], 200);
        }

        /**
         * Prepare and save record into database.
         */
        $record = new Record;

        $record->site_id = $site->id;
        $record->rid = data_get($request, 'rid');
        $record->ts = $ts;
        $record->anonymous_id = $anonymousId;
        $record->is_first_visit = $is_first_visit;
        $record->type = data_get($request, 'type');
        $record->title = data_get($request, 'title');
        $record->url = $url;
        $record->path = $path;
        $record->fragment = data_get($request, 'fragment');
        $record->query = data_get($request, 'query');
        $record->user_agent = $userAgent;
        $record->language = data_get($request, 'language');
        $record->referrer = data_get($request, 'referrer');
        $record->timezone = data_get($request, 'timezone');
        $record->country = $country;
        $record->screen = [
            'width' => data_get($request, 'screenWidth'),
            'height' => data_get($request, 'screenHeight'),
            'color_depth' => data_get($request, 'screenColorDepth'),
        ];
        $record->window = [
            'width' => data_get($request, 'windowHeight'),
            'height' => data_get($request, 'windowWidth'),
        ];

        $record->browser = $browser;
        $record->browser_version = $browserVersion;
        $record->platform = $platform;
        $record->platform_version = $platformVersion;
        $record->device = $device;
        $record->host = parse_url($url, PHP_URL_HOST);

        $record->custom_domain = data_get($request, 'customDomain');
        $record->custom_variable = data_get($request, 'customVariable');

        $record->save();

        /**
         * Return response.
         */
        return response()->json([
            'code' => '201',
            'status' => 'created',
            'message' => 'Record created.',
        ], 201);
    }

    /**
     * Update record from script HTTP POST request.
     *
     * @param  Request  $request
     * @return JSON
     */
    public function update(Request $request)
    {
        /**
         * Prepare data from beacon text/plain HTTP request.
         * Source : https://stackoverflow.com/questions/66199232/handling-text-plain-request-in-laravel-sent-via-navigator-sendbeacon
         */
        $data = json_decode($request->getContent(), true);

        /**
         * Prepare and update record into database.
         */
        $record = Record::where('rid', data_get($data, 'rid'))->first();

        $record->duration = data_get($data, 'duration');
        $record->scroll = data_get($data, 'scroll');

        $record->save();

        /**
         * Return response.
         */
        return response()->json([
            'code' => '200',
            'status' => 'updated',
            'message' => 'Record updated.',
        ], 201);
    }
}
