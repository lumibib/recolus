<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recolus:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Recolus';

    /**
     * Is the code already updated or not
     *
     * @var boolean
     */
    private $alreadyUpToDate;

    /**
     * Log from git pull
     *
     * @var array
     */
    private $pullLog = [];

    /**
     * Log from composer install
     *
     * @var array
     */
    private $composerLog = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('down');

        if ($this->confirm('Have you fetched the latest version (from git or manually) ?')) {
            $this->line("Good, go ahead");
        } else {
            if ($this->confirm('Would you like to pull latest version from Git?')) {

                $updateMethod = $this->choice('Which method would you like to use?', [
                    'automatic_git' => 'Automatically (git)',
                    'manual_git' => 'Manually (git)',
                ]);

                if ($updateMethod === 'automatic_git') {
                    if(!$this->runPull()) {

                        $this->error("An error occurred while executing 'git pull'. \nLogs:");

                        foreach($this->pullLog as $logLine) {
                            $this->info($logLine);
                        }

                        return;
                    }

                    if($this->alreadyUpToDate) {
                        $this->info("The application is already up-to-date");
                    }
                } elseif ($updateMethod === 'manual_git') {
                    $this->warn("Fetch latest version manually with git.");
                    $this->newLine();
                    $this->line("1. Fetch latest version running:");
                    $this->newLine();
                    $this->line('git fetch origin');
                    $this->line('git tag -l');
                    $this->line('git checkout CHOOSEN_TAG');
                    $this->newLine();
                    $this->line("2. Run 'php artisan reculus:update' again.");
                    $this->newLine();
                    return ;
                }
            } else {
                $this->warn("Please download latest version manually going to :");
                $this->newLine();
                $this->line("https://github.com/lumibib/recolus");
                $this->newLine();
                $this->line("1. Update the application files.");
                $this->line("2. Run 'php artisan reculus:update' again.");
                $this->newLine();
                return ;
            }
        }

        if ($this->confirm('Would you like to run composer update?')) {
            if(!$this->runComposer()) {

                $this->error("Error while updating composer files. \nLogs:");

                foreach($this->composerLog as $logLine) {
                    $this->info($logLine);
                }

                return;
            }
        }

        if ($this->confirm('Would you like to run migrations (update database)?')) {
            $this->call('migrate');
        }

        $this->call('cache:clear');
        $this->call('up');
        $this->info('Recolus updated');
    }

    /**
     * Run composer install process
     *
     * @return boolean
     */

    private function runComposer()
    {

    $process = new Process(['composer', 'install', '--no-dev', '-o', '--no-scripts']);
        $this->line("Running 'composer install'");

        $process->run(function($type, $buffer) {
            $this->composerLog[] = $buffer;
        });


        return $process->isSuccessful();

    }

    /**
     * Run git pull process
     *
     * @return boolean
     */

    private function runPull()
    {

        $process = new Process(['git', 'pull', 'origin', 'main']);
        $this->line("Running 'git pull'");

        $process->run(function($type, $buffer) {
            $this->pullLog[] = $buffer;

            if($buffer == "Already up to date.\n") {
                $this->alreadyUpToDate = TRUE;
            }

        });

        return $process->isSuccessful();

    }
}
