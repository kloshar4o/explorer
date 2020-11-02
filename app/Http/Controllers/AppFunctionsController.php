<?php

namespace App\Http\Controllers;

use App\Setting;
use Artisan;
use Doctrine\DBAL\Driver\PDOException;
use Schema;

class AppFunctionsController extends Controller
{
    /**
     * List of allowed settings to get from public request
     *
     * @var array
     */
    private $whitelist = [
        'footer_content',
        'get_started_description',
        'get_started_title',
        'pricing_description',
        'pricing_title',
        'feature_description_3',
        'feature_title_3',
        'feature_description_2',
        'feature_title_2',
        'feature_description_1',
        'feature_title_1',
        'features_description',
        'features_title',
        'header_description',
        'header_title',
        'section_get_started',
        'section_pricing_content',
        'section_feature_boxes',
        'section_features',
    ];

    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($static_content = null)
    {
        try {
            // Try to connect to database
            \DB::getPdo();

            // Check settings table
            $settings_table = Schema::hasTable('settings');
            $users_table = Schema::hasTable('users');

            // If settings table don't exist, then run migrations
            if ($users_table && !$settings_table) {
                Artisan::call('migrate', [
                    '--force' => true
                ]);
            }

            // Get all settings
            $settings = Setting::all();

        } catch (PDOException $e) {
            $connection = 'setup-database';
            $settings = null;
        }

        return view('index')
            ->with('settings', $settings ? json_decode($settings->pluck('value', 'name')->toJson()) : null);
    }


    /**
     * Clear application cache
     */
    public function flush_cache()
    {
        // Check if is demo
        if (env('APP_DEMO')) {
            return Demo::response_204();
        }

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
    }
}
