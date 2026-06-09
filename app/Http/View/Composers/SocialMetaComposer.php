<?php

namespace App\Http\View\Composers;

use App\Support\SocialMetaResolver;
use Illuminate\View\View;

class SocialMetaComposer
{
    public function __construct(private SocialMetaResolver $resolver) {}

    public function compose(View $view): void
    {
        if (($view->getData()['socialMeta'] ?? null) instanceof \App\Support\SocialMeta) {
            return;
        }

        $view->with('socialMeta', $this->resolver->resolve(request(), $view->getData()));
    }
}
