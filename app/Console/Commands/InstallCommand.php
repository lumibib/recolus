<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recolus:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Recolus';

    /**
     * Execute the console command.
     * Source : https://github.com/CachetHQ/Cachet/blob/2.4/app/Console/Commands/InstallCommand.php
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm('Do you want to configure Recolus before installing?')) {
            $this->configureEnvironmentFile();
            $this->configureKey();
            $this->configureDatabase();
            // $this->configureDrivers();
            $this->configureMail();
            $this->configureRecolus();
        }

        $this->configureUser();

        $this->line('Run migrations');
        $this->call('migrate', ['--force' => true]);

        $this->line('Run installation commands');
        // $this->call('storage:link');
        $this->call('cache:clear');

        $this->seedFakeData();

        $this->info('Recolus is installed ⚡');
    }

    /**
     * Copy the environment file.
     *
     * @return void
     */
    protected function configureEnvironmentFile()
    {
        $dir = app()->environmentPath();
        $file = app()->environmentFile();
        $path = "{$dir}/{$file}";

        if (file_exists($path)) {
            $this->line('Environment file already exists. Moving on.');

            return;
        }

        copy("$path.example", $path);
    }

    /**
     * Generate the app key.
     *
     * @return void
     */
    protected function configureKey()
    {
        $this->call('key:generate');
    }

    /**
     * Configure the database.
     *
     * @param  array  $default
     * @return void
     */
    protected function configureDatabase(array $default = [])
    {
        // Don't continue with these settings if we're not interested.
        if (! $this->confirm('Do you want to configure the database?')) {
            return;
        }
        $config = array_merge([
            'DB_DRIVER' => env('DB_DRIVER', null),
            'DB_HOST' => env('DB_HOST', null),
            'DB_DATABASE' => env('DB_DATABASE', null),
            'DB_USERNAME' => env('DB_USERNAME', null),
            'DB_PASSWORD' => env('DB_PASSWORD', null),
            'DB_PORT' => env('DB_PORT', null),
            'DB_PREFIX' => env('DB_PREFIX', null),
        ], $default);

        $config['DB_DRIVER'] = $this->choice('Which database driver do you want to use?', [
            'mysql' => 'MySQL',
            'pgsql' => 'PostgreSQL',
            'sqlite' => 'SQLite',
        ], $config['DB_DRIVER']);

        if ($config['DB_DRIVER'] === 'sqlite') {
            $config['DB_DATABASE'] = $this->ask('Please provide the full path to your SQLite file.', $config['DB_DATABASE']);
        } else {
            $config['DB_HOST'] = $this->ask("What is the host of your {$config['DB_DRIVER']} database?", $config['DB_HOST']);
            if ($config['DB_HOST'] === 'localhost' && $config['DB_DRIVER'] === 'mysql') {
                $this->warn("Using 'localhost' will result in the usage of a local unix socket. Use 127.0.0.1 if you want to connect over TCP");
            }

            $config['DB_DATABASE'] = $this->ask('What is the name of the database that Recolus should use?', $config['DB_DATABASE']);

            $config['DB_USERNAME'] = $this->ask('What username should we connect with?', $config['DB_USERNAME']);

            $config['DB_PASSWORD'] = $this->secret('What password should we connect with?', $config['DB_PASSWORD']);

            $config['DB_PORT'] = $config['DB_DRIVER'] === 'mysql' ? 3306 : 5432;
            if ($this->confirm('Is your database listening on a non-standard port number?')) {
                $config['DB_PORT'] = $this->anticipate('What port number is your database using?', [3306, 5432], $config['DB_PORT']);
            }
        }

        if ($this->confirm('Do you want to use a prefix on the table names?')) {
            $config['DB_PREFIX'] = $this->ask('Please enter the prefix now...', $config['DB_PREFIX']);
        }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (! $this->confirm('Are these settings correct?')) {
            return $this->configureDatabase($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure other drivers.
     *
     * @param  array  $default
     * @return void
     */
    protected function configureDrivers(array $default = [])
    {
        $config = array_merge([
            'CACHE_DRIVER' => null,
            'SESSION_DRIVER' => null,
            'QUEUE_DRIVER' => null,
        ], $default);

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        $config['CACHE_DRIVER'] = $this->choice('Which cache driver do you want to use?', [
            'apc' => 'APC(u)',
            'array' => 'Array',
            'database' => 'Database',
            'file' => 'File (default)',
            'memcached' => 'Memcached',
            'redis' => 'Redis',
        ], $config['CACHE_DRIVER']);

        // We need to configure Redis.
        if ($config['CACHE_DRIVER'] === 'redis') {
            $this->configureRedis();
        }

        $config['SESSION_DRIVER'] = $this->choice('Which session driver do you want to use?', [
            'apc' => 'APC(u)',
            'array' => 'Array',
            'database' => 'Database',
            'file' => 'File (default)',
            'memcached' => 'Memcached',
            'redis' => 'Redis',
        ], $config['SESSION_DRIVER']);

        // We need to configure Redis.
        if ($config['SESSION_DRIVER'] === 'redis') {
            $this->configureRedis();
        }

        $config['QUEUE_DRIVER'] = $this->choice('Which queue driver do you want to use?', [
            'null' => 'None',
            'sync' => 'Synchronous',
            'database' => 'Database (default)',
            'beanstalkd' => 'Beanstalk',
            'sqs' => 'Amazon SQS',
            'redis' => 'Redis',
        ], $config['QUEUE_DRIVER']);

        // We need to configure Redis, but only if the cache driver wasn't redis.
        if ($config['QUEUE_DRIVER'] === 'redis' && ($config['SESSION_DRIVER'] !== 'redis' || $config['CACHE_DRIVER'] !== 'redis')) {
            $this->configureRedis();
        }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (! $this->confirm('Are these settings correct?')) {
            return $this->configureDrivers($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure mail.
     *
     * @param  array  $config
     * @return void
     */
    protected function configureMail(array $config = [])
    {
        $config = array_merge([
            'MAIL_DRIVER' => null,
            'MAIL_HOST' => null,
            'MAIL_PORT' => null,
            'MAIL_USERNAME' => null,
            'MAIL_PASSWORD' => null,
            'MAIL_ADDRESS' => null,
            'MAIL_NAME' => null,
            'MAIL_ENCRYPTION' => null,
        ], $config);

        // Don't continue with these settings if we're not interested in notifications.
        if (! $this->confirm('Do you want Recolus to send mail notifications?')) {
            return;
        }

        $config['MAIL_DRIVER'] = $this->choice('What driver do you want to use to send notifications?', [
            'smtp' => 'SMTP',
            'mail' => 'Mail',
            'sendmail' => 'Sendmail',
            'mailgun' => 'Mailgun',
            'mandrill' => 'Mandrill',
            'ses' => 'Amazon SES',
            'sparkpost' => 'SparkPost',
            'log' => 'Log (Testing)',
        ]);

        if (! $config['MAIL_DRIVER'] === 'log') {
            if ($config['MAIL_DRIVER'] === 'smtp') {
                $config['MAIL_HOST'] = $this->ask('Please supply your mail server host');
            }

            $config['MAIL_ADDRESS'] = $this->ask('What email address should we send notifications from?');
            $config['MAIL_USERNAME'] = $this->ask('What username should we connect as?');
            $config['MAIL_PASSWORD'] = $this->secret('What password should we connect with?');
        }

        // Format the settings ready to display them in the table.
        $this->formatConfigsTable($config);

        if (! $this->confirm('Are these settings correct?')) {
            return $this->configureMail($config);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure Recolus.
     *
     * @param  array  $config
     * @return void
     */
    protected function configureRecolus(array $config = [])
    {
        $config = [];
        if ($this->confirm('Do you want to parameter the Recolus name and environment?')) {
            $config['APP_NAME'] = $this->ask('What is the application name?');
            $config['APP_URL'] = $this->ask('What is the application URL?');
            $config['APP_ENV'] = $this->choice('What is the application environment?', [
                'local' => 'Local',
                'production' => 'Production',
            ]);
            $config['APP_DEBUG'] = $this->choice('Would you like to debug the app?', [
                'true' => 'Yes (local environment)',
                'false' => 'No (production environment)',
            ]);
        }

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Configure the first user.
     *
     * @return void
     */
    protected function configureUser()
    {
        if (! $this->confirm('Do you want to create an admin user?')) {
            return;
        }

        // We need to refresh the config to get access to the newly connected database.
        $this->getFreshConfiguration();
        $this->call('migrate', ['--force' => true]);

        $user = [
            'name' => $this->ask('Please enter your username'),
            'email' => $this->ask('Please enter your email'),
            'password' => Hash::make($this->secret('Please enter your password')),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];

        User::create($user);
    }

    /**
     * Seed fake data.
     *
     * @return void
     */
    protected function seedFakeData()
    {
        if ($this->confirm('Do you want to seed some fake data?')) {
            $this->call('db:seed', ['--force' => true]);
            $this->info('Fake data seeded');
        }
    }

    /**
     * Configure the redis connection.
     *
     * @return void
     */
    protected function configureRedis()
    {
        $config = [
            'REDIS_HOST' => null,
            'REDIS_DATABASE' => null,
            'REDIS_PORT' => null,
        ];

        $config['REDIS_HOST'] = $this->ask('What is the host of your redis server?');
        $config['REDIS_DATABASE'] = $this->ask('What is the name of the database that Recolus should use?');
        $config['REDIS_PORT'] = $this->ask('What port should Recolus use?', 6379);

        foreach ($config as $setting => $value) {
            $this->writeEnv($setting, $value);
        }
    }

    /**
     * Format the configs into a pretty table that we can easily read.
     *
     * @param  array  $config
     * @return void
     */
    protected function formatConfigsTable(array $config)
    {
        $configRows = [];

        foreach ($config as $setting => $value) {
            $configRows[] = compact('setting', 'value');
        }

        $this->table(['Setting', 'Value'], $configRows);
    }

    /**
     * Boot a fresh copy of the application configuration.
     *
     * @return void
     */
    protected function getFreshConfiguration()
    {
        $app = require $this->laravel->bootstrapPath().'/app.php';
        $app->make(Kernel::class)->bootstrap();
    }

    /**
     * Writes to the .env file with given parameters.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    protected function writeEnv($key, $value)
    {
        $envKey = strtoupper($key);
        $envValue = env($envKey);

        $path = app()->environmentFilePath();

        if ($envValue !== null) {
            file_put_contents($path, str_replace(
                $envKey.'='.$envValue,
                $envKey.'='.$value,
                file_get_contents(app()->environmentFilePath())
            ));
        } else {
            /* Env not added */
        }
    }
}
