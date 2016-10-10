<?php

namespace Spatie\FragmentImporter;

use App\Models\Fragment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\FragmentImporter\Exceptions\FragmentFileNotFound;

class Importer
{
    /** @var bool */
    protected $updateExistingFragments = false;

    public function updateExistingFragments()
    {
        $this->updateExistingFragments = true;
    }

    public function import(string $path)
    {
        $this->guardAgainstInvalidPath($path);

        $this->loadFragments($path)->each(function (array $data) {
            $fragment = Fragment::firstOrNew(['group' => $data['group'], 'key' => $data['key']]);

            if (!$this->shouldImport($fragment)) {
                return;
            }

            $fragment->group = $data['group'];
            $fragment->key = $data['key'];
            $fragment->hidden = $data['hidden'];
            $fragment->contains_html = $data['contains_html'] ?? false;
            $fragment->description = $data['description'] ?? '';
            $fragment->draft = false;

            $this->locales()->each(function (string $locale) use ($fragment, $data) {
                $fragment->setTranslation($locale, $data["text_{$locale}"] ?? '');
            });

            $fragment->save();
        });
    }

    protected function guardAgainstInvalidPath(string $path)
    {
        if (!file_exists($path)) {
            throw FragmentFileNotFound::inPath($path);
        }
    }

    protected function loadFragments(string $path): Collection
    {
        return Excel::load($path)->all()->flatMap(function (RowCollection $sheet) {
            return $sheet->reject(function (CellCollection $row) {
                return empty(trim($row->group));
            })->map(function (CellCollection $row) use ($sheet) {
                return $row->put('hidden', $sheet->getTitle() === 'hidden')->toArray();
            });
        });
    }

    protected function shouldImport(Fragment $fragment): bool
    {
        if (!$fragment->exists) {
            return true;
        }

        return $this->updateExistingFragments;
    }

    protected function locales(): Collection
    {
        return collect(config('app.locales'))
            ->merge(config('app.backLocales'))
            ->unique();
    }
}
