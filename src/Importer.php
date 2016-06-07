<?php

namespace Spatie\FragmentImporter;

use App\Models\Fragment;
use Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;

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
        $this->loadFragments($path)->each(function (Fragment $fragment) {

            if (!$this->updateExistingFragments && Fragment::findByName($fragment->name)) {
                return;
            }

            $fragment->save();

        });
    }

    public function loadFragments(string $path): Collection
    {
        if (!file_exists($path)) {
            throw new \Exception("import file `{$path}` does not exist");
        }

        $reader = Excel::load($path);

        return $reader->all()->flatMap(function (RowCollection $rowCollection) {

            return $rowCollection->map(function (CellCollection $row) use ($rowCollection) {

                if (empty($row->name)) {
                    return;
                }

                if (!strlen(trim($row->name))) {
                    return;
                }

                $fragment = new Fragment();

                $fragment->name = $row->name;
                $fragment->hidden = ($rowCollection->getTitle() === 'hidden');
                $fragment->contains_html = $row->contains_html ?? false;
                $fragment->description = $row->description ?? '';
                $fragment->draft = 0;

                foreach (config('app.locales') as $locale) {
                    $fragment->translate($locale)->text = $row->{"text_{$locale}"} ?? '';
                }

                return $fragment;
            });
        });
    }
}
