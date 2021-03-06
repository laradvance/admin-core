<?php

namespace Encore\Admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'admin:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize Application';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->confirm('Have you run composer install --optimize-autoloader --no-dev ?')) {
            return;
        }
        $this->removeFilesAndDirectories();
        $this->resetSessions();
        $this->call('config:cache');
        $this->call('view:cache');
        $this->line('<info>Optimization Successful!</info>');
        if (! $this->confirm('Want to optimize Route Loading?')) {
            return;
        }
        $this->call('route:cache');
    }

    /**
     * Remove files and directories.
     *
     * @return void
     */
    protected function removeFilesAndDirectories()
    {
        $this->laravel['files']->deleteDirectory(base_path('tests'));
        $this->laravel['files']->deleteDirectory(storage_path('debugbar'));
        $this->laravel['files']->deleteDirectory(storage_path('framework/sessions'));
        $this->laravel['files']->deleteDirectory(public_path('uploads'));
        $this->laravel['files']->delete(base_path('.editorconfig'));
        $this->laravel['files']->delete(base_path('.env.example'));
        $this->laravel['files']->delete(base_path('.gitattributes'));
        $this->laravel['files']->delete(base_path('.gitignore'));
        $this->laravel['files']->delete(base_path('.styleci.yml'));
        $this->laravel['files']->delete(base_path('.travis.yml'));
        $this->laravel['files']->delete(base_path('LICENSE'));
        $this->laravel['files']->delete(base_path('README.md'));
        $this->laravel['files']->delete(base_path('phpunit.xml.dist'));
        $this->laravel['files']->delete(base_path('phpunit.xml'));
        $this->laravel['files']->delete(storage_path('logs/laravel.log'));
    }

    /**
     * Recreate Session Folder.
     *
     * @return void
     */
    protected function resetSessions()
    {
        $this->laravel['files']->makeDirectory(storage_path('framework/sessions'), 0755, true, true);
        $file = storage_path('framework/sessions').'/.gitignore';
        $contents = $this->getStub('gitignore');
        $this->laravel['files']->put($file, $contents);
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }
}
