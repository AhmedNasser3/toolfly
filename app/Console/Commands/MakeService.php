<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");

        if (File::exists($path)) {
            $this->error("Service {$name} already exists!");
            return;
        }

        File::ensureDirectoryExists(app_path('Services'));

        $stub = "<?php\n\nnamespace App\Services;\n\nclass {$name}\n{\n    // Service methods\n}\n";
        File::put($path, $stub);

        $this->info("Service {$name} created successfully.");
    }
}