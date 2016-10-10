<?php

namespace Spatie\FragmentImporter;

use App\Models\Fragment;
use Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class Exporter
{
    public static function sendExportToBrowser()
    {
        $exporter = new static();

        $exporter->generateExcel();
    }

    public function generateExcel()
    {
        Excel::create('fragments '.date('Y-m-d H:i:s'), function ($excel) {
            $this->addSheet($excel, 'fragments', $this->getVisibleFragments());
            $this->addSheet($excel, 'hidden', $this->getHiddenFragments());
        })->download('xlsx');
    }

    protected function addSheet(LaravelExcelWriter $excel, string $name, Collection $fragments)
    {
        $excel->sheet($name, function ($sheet) use ($fragments) {
            $sheet->freezeFirstRow();

            $sheet->cells('A1:Z1', function ($cells) {
                $cells->setFontWeight('bold');
                $cells->setBorder('node', 'none', 'solid', 'none');
            });

            $rowCounter = 1;

            $sheet->row($rowCounter++, $this->getHeaderColumns());

            foreach ($fragments as $fragment) {
                $fragmentProperties = [
                    $fragment['group'],
                    $fragment['key'],
                    $fragment['contains_html'],
                    $fragment['description'],
                ];

                $translatedFragmentProperties = Locales::forFragments()
                    ->map(function (string $locale) use ($fragment) {
                        return $fragment->getTranslation($locale);
                    });

                $sheet->row($rowCounter++, array_merge($fragmentProperties, $translatedFragmentProperties));
            }
        });
    }

    protected function getHeaderColumns(): array
    {
        return collect(['group', 'key', 'contains_html', 'description'])->merge(
            locales()->map(function (string $locale) {
                return "text_{$locale}";
            })->toArray()
        );
    }

    public function getVisibleFragments(): Collection
    {
        return $this->getFragments($hidden = false);
    }

    public function getHiddenFragments(): Collection
    {
        return $this->getFragments($hidden = true);
    }

    public function getFragments(bool $hidden): Collection
    {
        return Fragment::where('hidden', $hidden)
            ->orderBy('group')
            ->orderBy('key')
            ->get();
    }
}
