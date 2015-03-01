<?php namespace Pyrello\ProfileCallable;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ProfileCallableCommand extends Command
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'profile:post-process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Measure time taken and memory usage for various functions';

    protected $ops = 0;

    protected $start_mem;

    protected $start_time;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
//        register_shutdown_function([$this, 'shutdownMemory']);
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * Example usage:
     * php artisan profile:post-process callable --arguments='comma, separated, list'
     *
     * @return void
     */
    public function fire()
    {
        $callable = $this->argument('callable');

        // Only execute this if it is a callable function
        if (is_callable($callable)) {
            $this->info("Profiling [$callable]:");
            $args = (is_null($this->option('args'))) ? [] : preg_split('/\s?,\s/', $this->option('args'));
            $this->start_mem = memory_get_usage();
            $this->start_time = microtime(true);
            declare(ticks=1);
            register_tick_function([$this, 'countTicks']);
            try {
                call_user_func_array($callable, $args);
                unregister_tick_function([$this, 'countTicks']);
                $this->displayReport();
            } catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
            }
        } else {
            $this->error('Error: Invalid callable function supplied');
        }
    }

    public function shutdownMemory() {
        $this->info('Memory used at shutdown: ' . memory_get_usage()/1024/1024 . ' MiB');
        $this->info('Vars: ' . print_r(get_defined_vars(), true));
    }

    protected function displayReport()
    {
        $this->info('Time taken: ' . (microtime(true) - $this->start_time) . ' seconds');
        $this->info('Memory used: ' . (memory_get_peak_usage() - $this->start_mem) / 1024 / 1024 . ' MiB');
        $this->info('Operations: ' . $this->ops);
    }

    public function countTicks() {
        $this->ops++;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['callable', InputArgument::REQUIRED, 'The method being called.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['args', null, InputOption::VALUE_OPTIONAL, 'A comma-separated list of arguments to pass to the callable function', null],
        ];
    }
}
 