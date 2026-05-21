<?php

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../resources/views/sections'));

foreach ($files as $file) {
    if ($file->getExtension() !== 'php' && ! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }
    if (! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $content = file_get_contents($path);

    if (! str_starts_with($content, '@php $s = $pageSections')) {
        $key = basename($path, '.blade.php');
        $dir = basename(dirname($path));
        if ($dir === 'home' || $dir === 'about' || $dir === 'campaigns') {
            $content = '@php $s = $pageSections['.var_export($key, true).'] ?? []; @endphp'."\n".$content;
        }
    }

    $content = preg_replace(
        '/href="\{\{ route\(([^)]+)\) \}\}"/',
        'href="@sectionUrl($s[\'__link\' ?? \'link\'] ?? [\'type\' => \'route\', \'name\' => trim($1, \'"\')])"',
        $content
    );

    file_put_contents($path, $content);
}

echo "Done\n";
