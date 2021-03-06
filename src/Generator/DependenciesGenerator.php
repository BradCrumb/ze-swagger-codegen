<?php

namespace Swagger\Generator;

use Swagger\V30\Schema\Document;
use Swagger\Template;
use Swagger\Ignore;

class DependenciesGenerator extends AbstractGenerator
{
    /**
     * @var Template
     */
    protected $templateService;

    /**
     * @var HydratorGenerator
     */
    protected $hydratorGenerator;

    /**
     * @var ModelGenerator
     */
    protected $modelGenerator;

    /**
     * @var Ignore
     */
    protected $ignoreService;

    /**
     * Constructor
     * ---
     * @param Template $templateService
     * @param HydratorGenerator $hydratorGenerator
     * @param ModelGenerator $modelGenerator
     * @param Ignore $ignoreService
     */
    public function __construct(
        Template $templateService,
        HydratorGenerator $hydratorGenerator,
        ModelGenerator $modelGenerator,
        Ignore $ignoreService
    ) {
        $this->templateService = $templateService;
        $this->hydratorGenerator = $hydratorGenerator;
        $this->modelGenerator = $modelGenerator;
        $this->ignoreService = $ignoreService;
    }

    /**
     * @param  Document $document
     * @param  string   $namespace
     * @param  string $configPath
     */
    public function generateFromDocument(Document $document, string $namespace, string $configPath): bool
    {
        $dependencyConfigPath = rtrim($configPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . 'swagger.dependencies.global.php';

        if (!$this->ignoreService->isIgnored($dependencyConfigPath)) {
            $dependencies = $this->templateService->render('dependencies', [
                'models'    => $this->modelGenerator->getModelClasses($document, $namespace),
                'hydrators' => $this->hydratorGenerator->getHydratorClasses($document, $namespace)
            ]);

            $this->writeFile($dependencyConfigPath, $dependencies);

            return true;
        }

        return false;
    }
}
