<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;

use App\Models\Program;

class ProgramController extends Controller
{
    public function index()
    {
        $rows = Program::where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $programsCards = [];
        $accents = ['grad', 'orange', 'black'];

        foreach ($rows as $index => $program) {
            $description = trim((string) $program->short_desc);
            $full = trim((string) $program->full_desc);
            if ($full !== '' && $full !== $description) {
                $description = $description !== '' ? $description.' '.$full : $full;
            }

            $programsCards[] = [
                'title' => $program->title,
                'description' => $description,
                'image_url' => $program->image ? asset('uploads/programs/'.$program->image) : '',
                'accent' => $accents[$index % 3],
                'is_active' => $index === 0,
                'delay' => $index * 80,
            ];
        }

        $programsUseSlider = count($programsCards) > 0;
        $programsDetailsUrl = route('preview.programs');
        $programsHeader = [
            'chapter_num' => '03',
            'chapter_label' => 'Programs & Initiatives',
            'heading_html' => 'Programs &amp; Initiatives',
            'lede_text' => 'A focused view of flagship SBC initiatives across Chhattisgarh.',
            'explore_url' => route('preview.programs'),
            'explore_label' => 'Explore More',
        ];

        return view('preview::programs.index', compact(
            'programsUseSlider',
            'programsHeader',
            'programsCards',
            'programsDetailsUrl'
        ));
    }
}
