<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocialMetaResolver
{
    public function resolve(Request $request, array $viewData): SocialMeta
    {
        if (($viewData['socialMeta'] ?? null) instanceof SocialMeta) {
            return $viewData['socialMeta'];
        }

        $route = PageRoute::logicalName();

        return match ($route) {
            'stories.show' => $this->fromStory($viewData['story'] ?? []),
            'events.show' => $this->fromEvent($viewData['event'] ?? []),
            'reports.preview' => $this->fromReportPreview($viewData),
            'magazine' => $this->fromMagazine($viewData),
            'learning-corner.main' => $this->fromLearningMain($viewData),
            'learning-corner.sub' => $this->fromLearningSub($viewData),
            'learning-corner' => $this->fromNamedMeta(
                $viewData,
                'Learning Corner · ChhattisgarhABC',
                'Explore modules, videos, posters and training material for strengthening SBC practice.'
            ),
            'reports' => $this->fromReportsIndex($viewData),
            default => $this->fromPageDefaults($viewData, $route),
        };
    }

    private function fromStory(array $story): SocialMeta
    {
        $title = (string) ($story['title'] ?? '');

        return SocialMeta::make(
            title: $title !== '' ? $title.' · Stories · '.SocialMeta::SITE_NAME : null,
            description: $story['lede'] ?? $story['title'] ?? null,
            image: ShareImage::storyOgUrl($story['hero_image'] ?? null),
            url: SocialMeta::publicUrl(
                $story['share_url'] ?? (isset($story['slug']) ? route(PageRoute::named('stories.show'), $story['slug']) : null)
            ),
            type: 'article',
        );
    }

    private function fromEvent(array $event): SocialMeta
    {
        $title = (string) ($event['title'] ?? '');

        return SocialMeta::make(
            title: $title !== '' ? $title.' · Events · '.SocialMeta::SITE_NAME : null,
            description: $event['summary'] ?? $event['title'] ?? null,
            image: $event['image_url'] ?? $event['image'] ?? null,
            url: isset($event['slug']) ? route(PageRoute::named('events.show'), $event['slug']) : null,
            type: 'article',
        );
    }

    private function fromReportPreview(array $data): SocialMeta
    {
        $title = (string) ($data['reportTitle'] ?? '');
        $type = (string) ($data['reportType'] ?? 'Report');

        return SocialMeta::make(
            title: $title !== '' ? $title.' · Report Preview · '.SocialMeta::SITE_NAME : null,
            description: $title !== '' ? $title.' — '.$type.' preview from '.SocialMeta::SITE_NAME.'.' : null,
            image: $data['reportCover'] ?? $data['previewSrc'] ?? null,
            url: url()->current(),
            type: 'article',
        );
    }

    private function fromMagazine(array $data): SocialMeta
    {
        $title = (string) ($data['magazineTitle'] ?? '');
        $pages = $data['magazinePages'] ?? [];
        $image = is_array($pages) && $pages !== [] ? (string) $pages[0] : null;

        return SocialMeta::make(
            title: $title !== '' ? $title.' · '.SocialMeta::SITE_NAME : null,
            description: $title !== '' ? $title.' — interactive flipbook from '.SocialMeta::SITE_NAME.'.' : null,
            image: $image,
            url: isset($data['magazineSlug']) ? route(PageRoute::named('magazine'), ['slug' => $data['magazineSlug']]) : null,
            type: 'article',
        );
    }

    private function fromLearningMain(array $data): SocialMeta
    {
        $main = $data['main'] ?? [];
        $name = (string) ($main['name'] ?? '');

        return SocialMeta::make(
            title: $name !== '' ? $name.' · Learning Corner · '.SocialMeta::SITE_NAME : null,
            description: $main['description'] ?? ('Browse subtopics under '.$name.' — learning resources from '.SocialMeta::SITE_NAME.'.'),
            image: $this->firstResourceImage($data['resources'] ?? []),
            url: isset($main['id']) ? route(PageRoute::named('learning-corner.main'), ['main' => $main['id']]) : null,
            type: 'website',
        );
    }

    private function fromLearningSub(array $data): SocialMeta
    {
        $main = $data['main'] ?? [];
        $sub = $data['sub'] ?? [];
        $subName = (string) ($sub['name'] ?? '');
        $mainName = (string) ($main['name'] ?? '');

        return SocialMeta::make(
            title: $subName !== '' ? $subName.' · '.$mainName.' · Learning Corner' : null,
            description: $data['lcLede'] ?? ('Learning resources for '.$subName.' under '.$mainName.'.'),
            image: $this->firstResourceImage($data['resources'] ?? []),
            url: (isset($main['id'], $sub['id']))
                ? route(PageRoute::named('learning-corner.sub'), ['main' => $main['id'], 'sub' => $sub['id']])
                : null,
            type: 'website',
        );
    }

    private function fromReportsIndex(array $data): SocialMeta
    {
        return SocialMeta::make(
            title: $data['metaTitle'] ?? $data['pageTitle'] ?? 'Reports and Insights · '.SocialMeta::SITE_NAME,
            description: $data['metaDescription'] ?? $data['pageLede'] ?? null,
            image: $this->firstReportCover($data['reports'] ?? []),
            url: route(PageRoute::named('reports')),
            type: 'website',
        );
    }

    private function fromNamedMeta(array $data, string $fallbackTitle, string $fallbackDescription): SocialMeta
    {
        return SocialMeta::make(
            title: $data['metaTitle'] ?? $fallbackTitle,
            description: $data['metaDescription'] ?? $fallbackDescription,
            image: $this->firstResourceImage($data['resources'] ?? []),
            url: url()->current(),
            type: 'website',
        );
    }

    private function fromPageDefaults(array $data, ?string $route): SocialMeta
    {
        $pageContent = is_array($data['pageContent'] ?? null) ? $data['pageContent'] : [];
        $sections = is_array($data['sections'] ?? null) ? $data['sections'] : [];
        $sectionMeta = is_array($sections['meta'] ?? null) ? $sections['meta'] : [];

        $title = $data['metaTitle']
            ?? $pageContent['meta_title']
            ?? $data['pageTitle']
            ?? $this->routeFallbackTitle($route);

        $description = $data['metaDescription']
            ?? $pageContent['meta_description']
            ?? $data['pageLede']
            ?? $data['lcLede']
            ?? $sectionMeta['meta_description']
            ?? null;

        $image = $this->firstProgramImage($data['programsCards'] ?? [])
            ?? $this->firstReportCover($data['reports'] ?? [])
            ?? $this->firstResourceImage($data['resourcePeople'] ?? $data['resources'] ?? [])
            ?? $this->firstMemberImage($data['memberPaginator'] ?? null);

        return SocialMeta::make(
            title: $title,
            description: $description,
            image: $image,
            url: url()->current(),
            type: 'website',
        );
    }

    private function routeFallbackTitle(?string $route): ?string
    {
        return match ($route) {
            'home' => SocialMeta::DEFAULT_TITLE,
            'about' => 'About the Alliance · '.SocialMeta::SITE_NAME,
            'programs' => 'Programs and Initiatives · '.SocialMeta::SITE_NAME,
            'members' => 'Our Members · '.SocialMeta::SITE_NAME,
            'resources' => 'SBC Resource Pool · '.SocialMeta::SITE_NAME,
            'get-involved' => 'Get Involved · '.SocialMeta::SITE_NAME,
            'contact' => 'Contact · '.SocialMeta::SITE_NAME,
            'campaigns' => 'Campaigns · '.SocialMeta::SITE_NAME,
            'stories' => 'Impact Stories · '.SocialMeta::SITE_NAME,
            'events' => 'Events · '.SocialMeta::SITE_NAME,
            default => null,
        };
    }

    private function firstResourceImage(array $resources): ?string
    {
        foreach ($resources as $resource) {
            if (is_object($resource)) {
                $image = $resource->image_url ?? $resource->image ?? null;
            } else {
                $image = $resource['image_url'] ?? $resource['image'] ?? null;
            }

            if (is_string($image) && $image !== '' && $this->isShareableImage($image)) {
                return $image;
            }
        }

        return null;
    }

    private function firstProgramImage(array $cards): ?string
    {
        foreach ($cards as $card) {
            $image = is_array($card) ? ($card['image_url'] ?? null) : null;

            if (is_string($image) && $image !== '') {
                return $image;
            }
        }

        return null;
    }

    private function firstReportCover(array $reports): ?string
    {
        foreach ($reports as $report) {
            $cover = is_array($report) ? ($report['cover'] ?? null) : null;

            if (is_string($cover) && $cover !== '') {
                return $cover;
            }
        }

        return null;
    }

    private function firstMemberImage(mixed $paginator): ?string
    {
        if (! is_object($paginator) || ! method_exists($paginator, 'getCollection')) {
            return null;
        }

        foreach ($paginator->getCollection() as $member) {
            $image = $member->image_url ?? null;

            if (is_string($image) && $image !== '') {
                return $image;
            }
        }

        return null;
    }

    private function isShareableImage(string $url): bool
    {
        $path = strtolower(parse_url($url, PHP_URL_PATH) ?? $url);

        return ! Str::endsWith($path, ['.pdf', '.mp4', '.webm', '.mov']);
    }
}
