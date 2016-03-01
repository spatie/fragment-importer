<?php

namespace Spatie\FragmentImporter;

use App\Models\Fragment;
use Excel;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

class Importer
{
    /** @var string */
    protected $importFile;

    /** @var bool */
    protected $updateExistingFragments = false;

    public function updateExistingFragments() : Importer
    {
        $this->updateExistingFragments = true;

        return $this;
    }

    public function import(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("import file `{$path}` does not exist");
        }

        Excel::load($path, function (LaravelExcelReader $reader) {

            $reader->all()->each(function (RowCollection $rowCollection) {

                $rowCollection->each(function (CellCollection $row) use ($rowCollection) {
                    
                    if (empty($row->name)) {
                        return;
                    }
                    
                    $fragment = Fragment::findByName($row->name);

                    if ($fragment && !$this->updateExistingFragments) {
                        return;
                    }

                    if (!strlen(trim($row->name))) {
                        return;
                    }

                    $fragment = $fragment ?? new Fragment();

                    $fragment->name = $row->name;
                    $fragment->hidden = ($rowCollection->getTitle() === 'hidden');
                    $fragment->contains_html = $row->contains_html ?? false;
                    $fragment->description = $row->description ?? '';
                    $fragment->draft = 0;

                    foreach (config('app.locales') as $locale) {
                        $fragment->translate($locale)->text = $row->{"text_{$locale}"} ?? '';
                    }

                    $fragment->save();
                });
            });
        });
    }
}
