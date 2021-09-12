<?php /** @noinspection PhpUnused */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IdeHelperDevRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ide-helper:dev-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Runs 'generate' and 'meta' ide-helper commands if in dev env.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (app()->isLocal()) {
            Artisan::call('ide-helper:generate');
            Artisan::call('ide-helper:meta');
        }
        return 0;
    }
}
