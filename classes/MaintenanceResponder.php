<?php

declare(strict_types=1);

namespace Pensoft\Maintenance\Classes;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Pensoft\Maintenance\Contracts\ResponseMaker;
use Cms\Classes\Theme;

final class MaintenanceResponder implements ResponseMaker
{
    public const HTTP_STATUS_CODE = 503;

    public function __construct(
        private readonly Request $request,
        private readonly ResponseFactory $responseFactory,
        private readonly Translator $translator,
        private readonly ViewFactory $view,
        private readonly Repository $config
    ) {
    }

    public function isAssocArray(mixed $arr): bool
    {
        if (gettype($arr) !== 'array') {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function isDownForMaintenance(): bool
    {
        return file_exists(public_path('site') . '/down');
    }

    public function getResponse(): Response
    {
        if ($this->request->ajax()) {
            if ($this->request->hasHeader('X-OCTOBER-REQUEST-HANDLER')) {
                return $this->responseFactory->make(
                    $this->translator->trans('vdlp.maintenance::lang.responses.ajax.message'),
                    self::HTTP_STATUS_CODE
                );
            }

            return $this->responseFactory->json([
                'error' => $this->translator->trans('vdlp.maintenance::lang.responses.ajax.message'),
            ], self::HTTP_STATUS_CODE);
        }

        $view = $this->view->file($this->getMaintenancePagePath(), [
            'locale' => $this->translator->getLocale(),
            'app_name' => $this->config->get('app.name'),
        ]);

        return $this->responseFactory->make($view, self::HTTP_STATUS_CODE);
    }

    private function getMaintenancePagePath(): string
    {
        $theme = Theme::getActiveTheme();
        if (file_exists($page = themes_path($theme->getDirName().'/pages/503.htm'))) {
            return $page;
        }

        return __DIR__ . '/../assets/503.htm';
    }
}