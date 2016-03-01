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
        $exporter = new static;

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
                    $fragment['name'],
                    $fragment['contains_html'],
                    $fragment['description'],
                ];

                $translatedFragmentProperties = array_map(function ($locale) use ($fragment) {
                    return $fragment->translate($locale)->text;

                }, config('app.locales'));

                $sheet->row($rowCounter++, array_merge($fragmentProperties, $translatedFragmentProperties));
            }
        });
    }

    protected function getHeaderColumns() : array
    {
        $textColumnNames = array_map(function (string $locale) {
           return "text_{$locale}";
        }, config('app.locales'));

        return array_merge(['name', 'contains_html', 'description'], $textColumnNames);
    }

    public function getVisibleFragments() : Collection
    {
        return $this->getFragments($hidden = false);
    }

    public function getHiddenFragments() : Collection
    {
        return $this->getFragments($hidden = true);
    }

    public function getFragments(bool $hidden) : Collection
    {
        return Fragment::where('hidden', $hidden)->orderBy('name')->get();
    }
}
