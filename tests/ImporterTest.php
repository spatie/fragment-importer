<?php

namespace Spatie\FragmentImporter\Test;

use App\Models\Fragment;
use Spatie\FragmentImporter\Importer;

class ImporterTest extends TestCase
{
    /** @test */
    public function it_can_import_fragments()
    {
        $this->performImport();

        $this->assertCount(6, Fragment::all());
    }

    public function it_can_determine_if_a_fragment_contains_html()
    {

    }

    public function it_can_determine_if_a_fragment_is_hidden()
    {

    }

    public function it_doesnt_overwrite_existing_fragments()
    {

    }

    public function it_overwrites_existing_fragments_if_the_update_flag_is_set()
    {

    }

    protected function performImport(bool $updateExistingFragments = false)
    {
        $importer = app(Importer::class);

        if ($updateExistingFragments) {
            $importer->updateExistingFragments();
        }

        $importer->import(__DIR__.'/fixtures/fragments.xlsx');
    }
}
