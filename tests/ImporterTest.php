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

    /** @test */
    public function it_can_import_fragment_translations()
    {
        $this->performImport();

        $fragment = Fragment::where('group', 'fragment')
            ->where('key', 'first')
            ->first();

        $this->assertEquals('Een', $fragment->translate('text', 'nl'));
        $this->assertEquals('Un', $fragment->translate('text', 'fr'));
    }

    /** @test */
    public function it_can_determine_if_a_fragment_contains_html()
    {
        $this->performImport();

        $withoutHtml = Fragment::where('name', 'fragment.first')->first();
        $withHtml = Fragment::where('name', 'fragment.withHtml')->first();

        $this->assertFalse($withoutHtml->contains_html);
        $this->assertTrue($withHtml->contains_html);
    }

    /** @test */
    public function it_can_determine_if_a_fragment_is_hidden()
    {
        $this->performImport();

        $notHidden = Fragment::where('name', 'fragment.first')->first();
        $hidden = Fragment::where('name', 'fragment.hidden')->first();

        $this->assertFalse($notHidden->hidden);
        $this->assertTrue($hidden->hidden);
    }

    /** @test */
    public function it_doesnt_overwrite_existing_fragments()
    {
        $fragment = new Fragment([
            'name'          => 'fragment.first',
            'hidden'        => false,
            'contains_html' => false,
            'draft'         => false,
        ]);
        $fragment->setTranslation('text', 'nl', 'Hallo');
        $fragment->save();

        $this->performImport();

        $newFragment = Fragment::where('name', 'fragment.first')->first();

        $this->assertEquals('Hallo', $newFragment->translate('text', 'nl'));
    }

    /** @test */
    public function it_overwrites_existing_fragments_if_the_update_flag_is_set()
    {
        $fragment = new Fragment([
            'name'          => 'fragment.first',
            'hidden'        => false,
            'contains_html' => false,
            'draft'         => false,
        ]);
        $fragment->setTranslation('text', 'nl', 'Hallo');
        $fragment->save();

        $this->performImport(true);

        $newFragment = Fragment::where('name', 'fragment.first')->first();

        $this->assertEquals('Een', $newFragment->translate('text', 'nl'));
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
