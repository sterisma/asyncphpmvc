<?php

namespace App\Controllers;

use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;
use React\Filesystem\AdapterInterface;
use App\Core\BaseController;
use App\Core\File;
use App\Core\JsonResponse;
use App\Exceptions\FileNotFound;

final class StaticFilesController extends BaseController
{
    private AdapterInterface $filesystem;
    private string $root;

    public function __construct(AdapterInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->root = dirname(__DIR__, 2).'/public';

    }

    private function readFile(string $filepath)
    {
        return $this->filesystem->detect($filepath)
            ->then(function () use ($filepath){
                $file = $this->filesystem->file($filepath);
                $mimeType = \Defr\PhpMimeType\MimeType::get($file->name());
                return $file->getContents()
                    ->then(function (string $contents) use ($file, $mimeType) {
                        return new File($file->name(), $contents, $mimeType);
                    });
            }, function () use ($filepath) {
                throw new FileNotFound($filepath. " is not found!");
            });
    }

    public function serve(ServerRequestInterface $request, $file)
    {
        $filepath = $this->root.'/'.$file;
        return $this->readFile($filepath)
            ->then(function (File $file) {
                return new Response(
                    200,
                    ['Content-Type' => $file->mimeType],
                    $file->content
                );
            })
            ->catch(function (FileNotFound $e) {
                return JsonResponse::notFound($e->getMessage());
            })
            ->catch(function (\Exception $e) {
                return JsonResponse::internalServerError($e->getMessage());
            });
    }

    public function redirect(ServerRequestInterface $request, string $controller, $file, ?string $subcon=null)
    {
        return $this->serve($request, $file);
    }
}