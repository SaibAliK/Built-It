<?php

namespace App\Traits;

use Google_Client;
use Google_Service_Analytics;

trait GoogleAnalytics
{
    function initializeAnalytics()
    {
        // Creates and returns the Analytics Reporting service object.

        // Use the developers console and download your service account
        // credentials in JSON format. Place them in this directory or
        // change the key file location if necessary.
        $KEY_FILE_LOCATION = __DIR__ . '/analytics.json';

        // Create and configure a new client object.
        $client = new Google_Client();
        $client->setApplicationName("Hello Analytics Reporting");
        try {
            $client->setAuthConfig($KEY_FILE_LOCATION);
        } catch (\Exception $e) {
            dd($e);
        }
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

        return $analytics;
    }

    function getFirstProfileId($analytics)
    {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw dd('No views (profiles) found for this user.');
                }
            } else {
                throw dd('No properties found for this user.');
            }
        } else {
            throw dd('No accounts found for this user.');
        }
    }

    function getActiveUsers($analytics, $profileId)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
//        $countries = ['dimensions' => 'rt:country'];
        $data = $analytics->data_realtime->get(
            'ga:' . $profileId,
            'rt:activeUsers');
        // return $data;
        $totalUsers = 0;
        if ($data->getRows()) {
            if (count($data->getRows()) > 0) {

                // Get the profile name.
                $profileName = $data->getProfileInfo()->getProfileName();

                // Get the entry for the first entry in the first row.
                $rows = $data->getRows();
                $totalUsers = $rows[0][0];

            }
        }

        return $totalUsers;
    }

    function geographicalUsers($analytics, $profileId)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        $countries = ['dimensions' => 'ga:country,ga:countryIsoCode'];
        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            'ga:users',
            $countries);
        $usersDetail = [];
        if ($results->getRows()) {
            if (count($results->getRows()) > 0) {
                for ($i = 0; $i < count($results->getRows()); $i++) {
                    $rows = $results->getRows();
                    $temp = new \stdClass();
                    $temp->code3 = 'PAK';
                    $temp->name = $rows[$i][0];
                    $temp->y = (int)$rows[$i][2];
                    $temp->code = $rows[$i][1];
                    $usersDetail[] = $temp;
                }
                // Get the profile name.
                $profileName = $results->getProfileInfo()->getProfileName();

            }
        }

        return json_encode($usersDetail);
    }

    function operationgSystemUsers($analytics, $profileId)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        $countries = ['dimensions' => 'ga:operatingSystem'];
        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            'ga:users',
            $countries);

        $usersDetail = [];

        if ($results->getRows()) {
            if (count($results->getRows()) > 0) {
                for ($i = 0; $i < count($results->getRows()); $i++) {
                    $rows = $results->getRows();
                    $temp = new \stdClass();
                    $temp->name = $rows[$i][0];
                    $temp->y = (int)$rows[$i][1];
                    $temp->drilldown = $rows[$i][0];
                    $usersDetail[] = $temp;
                }
                // Get the profile name.
                $profileName = $results->getProfileInfo()->getProfileName();

            }
        }

        return json_encode($usersDetail);
    }

    function newOldUsers($analytics, $profileId)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        $countries = ['dimensions' => 'ga:userType'];
        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            'ga:users',
            $countries);

        $usersDetail = [];
        if ($results->getRows()) {
            if (count($results->getRows()) > 0) {
                for ($i = 0; $i < count($results->getRows()); $i++) {
                    $rows = $results->getRows();
                    $temp = new \stdClass();
                    $temp->name = $rows[$i][0];
                    $temp->y = (int)$rows[$i][1];
                    $temp->sliced = true;
                    $temp->selectd = true;
                    $usersDetail[] = $temp;
                }
                // Get the profile name.
                $profileName = $results->getProfileInfo()->getProfileName();

            }
        }
        return json_encode($usersDetail);
    }

    function dailySessionUsers($analytics, $profileId)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        $countries = ['dimensions' => 'ga:date'];
        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            'ga:users',
            $countries);

        $users = [];
        $dates = [];
        if ($results->getRows()) {
            if (count($results->getRows()) > 0) {
                for ($i = 0; $i < count($results->getRows()); $i++) {
                    $rows = $results->getRows();
                    $timeStamp = strtotime($rows[$i][0]);
                    $date = date('d/m/y', $timeStamp);
                    array_push($dates, $date);
                    array_push($users, (int)$rows[$i][1]);
                }
                // Get the profile name.
                $profileName = $results->getProfileInfo()->getProfileName();

            }
        }

        return [$dates, $users];
    }

    function getAnalyticsStatistics($analytics, $profileId)
    {
//        'start-date=30daysAgo&end-date=today&ids=ga:181812739&metrics=ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits,ga:AvgSessionDuration&dimensions=ga:pagePath&sort=-ga:pageviews'
        $dimensions = ['dimensions' => 'ga:date'];
        $sort = ['sort' => 'ga:pageviews'];
        $metrics = ['metrics' => 'ga%3Apageviews%2Cga%3AuniquePageviews%2Cga%3AtimeOnPage%2Cga%3Abounces%2Cga%3Aentrances%2Cga%3Aexits%2Cga%3AAvgSessionDuration'];
        $results = $analytics->data_ga->get(
            'ga:' . $profileId,
            '30daysAgo',
            'today',
            $metrics,
            $dimensions,
            $sort
        );

        return $results->getRows();
    }
}
