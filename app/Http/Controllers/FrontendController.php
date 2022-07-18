<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FrontendController extends Controller
{
    const EXCLUDED_URI = [
        // 'admin',
        // 'admin/*',
        'api',
        'api/*',
        'glide',
        'glide/*',
        'uploads',
        'uploads/*',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param string|null $path
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function __invoke(Request $request, $path = null)
    {
        if (
            (!$request->isMethod('GET') && !$request->isMethod('HEAD')) ||
            $request->is(static::EXCLUDED_URI)
        ) {
            abort(404);
        }

        if ($path) {
            $path = trim($path, '/');
        }

        $extension = null;
        if ($path) {
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            if (File::isReadable(base_path("storage/frontend/{$path}"))) {
                switch ($extension) {
                    case 'css':
                        $mime = 'text/css';
                        break;
                    case 'html':
                        $mime = 'text/html';
                        break;
                    case 'js':
                        $mime = 'application/javascript';
                        break;
                    case 'json':
                        $mime = 'application/json';
                        break;
                    case 'svg':
                        $mime = 'image/svg+xml';
                        break;
                    default:
                        // $mime = MimeTypes::getInstance()->guess(base_path("storage/frontend/{$path}"));
                        $mime = File::mimeType(base_path("storage/frontend/{$path}"));
                        break;
                }

                return new BinaryFileResponse(base_path("storage/frontend/{$path}"), 200, [
                    'Content-Type' => $mime,
                ], false);
            }
        }

        if (($path && $extension) || !File::isReadable(base_path("storage/frontend/index.html"))) {
            abort(404);
        }

        return new BinaryFileResponse(base_path("storage/frontend/index.html"), 200, [
            'Content-Type' => 'text/html; charset=utf-8'
        ], false);
    }
}
