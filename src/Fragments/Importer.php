<?php

namespace App\FragmentImporter;

use App\Models\Fragment;
use App\Repositories\FragmentRepository;
use Excel;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

class Importer
{
    /** @var \App\Repositories\FragmentRepository */
    protected $fragmentRepository;

    /** @var string */
    protected $importFile;

    /** @var bool */
    protected $updateExistingFragments = false;

    public function __construct(FragmentRepository $fragmentRepository)
    {
        $this->fragmentRepository = $fragmentRepository;
    }

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
                    $fragment = $this->fragmentRepository->findByName($row->name);

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

                    foreach (config('app.fragmentLocales') as $locale) {
                        $fragment->translate($locale)->text = $row->{"text_{$locale}"} ?? '';
                    }

                    $fragment->save();
                });
            });
        });
    }
}
