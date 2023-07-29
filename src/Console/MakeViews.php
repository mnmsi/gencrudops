<?php

namespace Mnmsi\GenCrudOps\Console;

use Illuminate\Console\Command;
use Mnmsi\GenCrudOps\Services\MakeGlobalService;
use Mnmsi\GenCrudOps\Services\MakeViewsService;
use Illuminate\Support\Facades\File;
use Mnmsi\GenCrudOps\Services\PathsAndNamespacesService;

class MakeViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:views {directory} {columns}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make views';

    public MakeViewsService          $makeViewsService;
    public MakeGlobalService         $makeGlobalService;
    public PathsAndNamespacesService $pathsAndNamespacesService;

    public function __construct(
        MakeViewsService          $makeViewsService,
        MakeGlobalService         $makeGlobalService,
        PathsAndNamespacesService $pathsAndNamespacesService
    )
    {
        parent::__construct();
        $this->makeViewsService          = $makeViewsService;
        $this->makeGlobalService         = $makeGlobalService;
        $this->pathsAndNamespacesService = $pathsAndNamespacesService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $templateViewsDirectory          = config('gencrudops.views_style_directory');
        $separateStyleAccordingToActions = config('gencrudops.separate_style_according_to_actions');

        if (!File::isDirectory($this->pathsAndNamespacesService->getGencrudopsViewsStubCustom($templateViewsDirectory))) {
            if ($templateViewsDirectory == 'default-views') {
                $this->error("Publish the default theme with: php artisan vendor:publish --provider=\"Mnmsi\GenCrudOps\GencrudopsServiceProvider\" or create your own default-views directory here: " . $this->pathsAndNamespacesService->getGencrudopsViewsStub());
            } else {
                $this->error("Do you have created a directory called " . $templateViewsDirectory . " here: " . $this->pathsAndNamespacesService->getGencrudopsViewsStub() . '?');
            }
            return;
        } else {
            $stubs = ['index', 'create', 'edit', 'show'];
            // check if all stubs exist
            foreach ($stubs as $stub) {
                if (!File::exists($this->pathsAndNamespacesService->getGencrudopsViewsStubCustom($templateViewsDirectory) . DIRECTORY_SEPARATOR . $stub . '.stub')) {
                    $this->error('Please create this file: ' . $this->pathsAndNamespacesService->getGencrudopsViewsStubCustom($templateViewsDirectory) . DIRECTORY_SEPARATOR . $stub . '.stub');
                    return;
                }
            }
        }

        // we create our variables to respect the naming conventions
        $directoryName    = $this->argument('directory');
        $namingConvention = $this->makeGlobalService->getNamingConvention($directoryName);

        $columns = $this->argument('columns');
        // if the columns argument is empty, we create an empty array else we explode on the comma
        $columns = ($columns == '') ? [] : explode(',', $columns);

        /* VIEWS */

        // if the directory doesn't exist we create it
        $this->makeViewsService->createDirectoryViews($namingConvention);


        /* index view */

        $contentIndex = $this->makeViewsService->findAndReplaceIndexView($columns, $templateViewsDirectory, $namingConvention, $separateStyleAccordingToActions);
        $this->makeViewsService->createFileOrError($namingConvention, $contentIndex, 'index.blade.php');

        /* create view */
        $contentCreate = $this->makeViewsService->findAndReplaceCreateView($columns, $templateViewsDirectory, $namingConvention, $separateStyleAccordingToActions);
        $this->makeViewsService->createFileOrError($namingConvention, $contentCreate, 'create.blade.php');

        /* show view */
        $contentShow = $this->makeViewsService->findAndReplaceShowView($templateViewsDirectory, $namingConvention, $separateStyleAccordingToActions);
        $this->makeViewsService->createFileOrError($namingConvention, $contentShow, 'show.blade.php');

        /* edit view */
        $contentEdit = $this->makeViewsService->findAndReplaceEditView($columns, $templateViewsDirectory, $namingConvention, $separateStyleAccordingToActions);
        $this->makeViewsService->createFileOrError($namingConvention, $contentEdit, 'edit.blade.php');
    }
}
