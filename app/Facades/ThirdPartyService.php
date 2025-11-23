<?php

namespace App\Facades;

use App\Services\FirebaseAnalyticsService;
use App\Services\FirebaseFCMService;
use App\Services\GoogleCalendarService;
use App\Services\GoogleDriverService;
use App\Services\GoogleHangoutService;
use App\Services\GoogleSpreadSheetService;

class ThirdPartyService
{
    // Define the services to use
    const GOOGLE_CHAT = 'chat';
    const GOOGLE_CALENDAR = 'calendar';
    const GOOGLE_DRIVER = 'driver';
    const GOOGLE_SPREADSHEET = 'spread_sheets';
    const FIREBASE_FCM = 'fcm';
    const FIREBASE_ANALYTICS = 'analytics';

    /**
     * Facade method initialize services.
     *
     * @param string service Corresponding to the services declared above.
     * @return class method.
     */
    public function initService($service)
    {
        switch ($service) {
            case self::GOOGLE_CHAT:
                return new GoogleHangoutService();
            case self::GOOGLE_CALENDAR:
                return new GoogleCalendarService();
            case self::GOOGLE_DRIVER:
                return new GoogleDriverService();
            case self::FIREBASE_FCM:
                return new FirebaseFCMService();
            case self::FIREBASE_ANALYTICS:
                return new FirebaseAnalyticsService();
            case self::GOOGLE_SPREADSHEET:
                return new GoogleSpreadSheetService();
            default:
                return "Service not found";
        }
    }
}