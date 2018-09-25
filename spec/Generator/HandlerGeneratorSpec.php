<?php

namespace spec\Swagger\Generator;

use Swagger\Generator\HandlerGenerator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swagger\Template;
use Swagger\V30\Object\Document;
use Swagger\V30\Object\PathItem;
use org\bovigo\vfs\vfsStream;
use Swagger\Ignore;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
class HandlerGeneratorSpec extends ObjectBehavior
{
    public function let(
        Template $templateService,
        Ignore $ignoreService
    ) {
        $this->beConstructedWith($templateService, $ignoreService);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(HandlerGenerator::class);
    }

    public function it_can_generate_from_path_item(
        PathItem $pathItem,
        Template $templateService,
        Ignore $ignoreService
    ) {
        vfsStream::setup('namespacePath');

        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->shouldBeCalled();

        $pathItem->getOperations()->willReturn([]);

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromPathItem($pathItem, 'test', vfsStream::url('namespacePath'), 'App')->shouldBeString();
    }

    public function it_can_generate_from_document(
        Document $document,
        PathItem $pathItem,
        Template $templateService,
        Ignore $ignoreService
    ) {
        vfsStream::setup('namespacePath');

        $document->getPaths()->willReturn([
            'test' => $pathItem
        ]);

        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->willReturn(Argument::type('string'));
        $templateService->render('handler', [
            'className'  => "TestHandler",
            'namespace'  => 'App\Handler',
            'operationMethods' => []
        ])->shouldBeCalled();

        $pathItem->getOperations()->willReturn([]);

        $ignoreService->isIgnored(Argument::type('string'))->willReturn(false);
        $ignoreService->isIgnored(Argument::type('string'))->shouldBeCalled();

        $this->generateFromDocument($document, vfsStream::url('namespacePath'), 'App');
    }
}
