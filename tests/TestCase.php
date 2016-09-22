<?php

namespace Spatie\FragmentImporter\Test;

use App\Models\Fragment;
use File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Maatwebsite\Excel\ExcelServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
        $this->setUpStubs();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getPackageProviders($app)
    {
        return [
            ExcelServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory($this->getTempDirectory());

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory() . '/database.sqlite',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('app.locales', ['nl', 'fr']);
        $app['config']->set('app.backLocales', ['nl']);
    }

    protected function setUpDatabase(Application $app)
    {
        file_put_contents($this->getTempDirectory() . '/database.sqlite', null);

        $app['db']->connection()->getSchemaBuilder()->create('fragments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->text('text');
            $table->string('description')->nullable();
            $table->boolean('contains_html')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('draft')->default(true);
            $table->timestamps();
        });
    }

    protected function setUpStubs()
    {
        if ( ! class_exists(Fragment::class)) {
            require_once __DIR__ . '/stubs/Fragment.php';
        }
    }

    protected function initializeDirectory(string $directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }

        File::makeDirectory($directory);
    }

    protected function getTempDirectory(string $suffix = ''): string
    {
        return __DIR__ . '/temp' . ($suffix == '' ? '' : '/' . $suffix);
    }
}
