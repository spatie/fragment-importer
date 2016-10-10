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

        $fragment = $this->getFragment('fragment', 'first');

        $this->assertEquals('Een', $fragment->getTranslation('nl'));
        $this->assertEquals('Un', $fragment->getTranslation('fr'));
    }

    /** @test */
    public function it_can_determine_if_a_fragment_contains_html()
    {
        $this->performImport();

        $withoutHtml = $this->getFragment('fragment', 'first');
        $withHtml = $this->getFragment('fragment', 'withHtml');

        $this->assertFalse($withoutHtml->contains_html);
        $this->assertTrue($withHtml->contains_html);
    }

    /** @test */
    public function it_can_determine_if_a_fragment_is_hidden()
    {
        $this->performImport();

        $notHidden = $this->getFragment('fragment', 'first');
        $hidden =  $this->getFragment('fragment', 'hidden');

        $this->assertFalse($notHidden->hidden);
        $this->assertTrue($hidden->hidden);
    }

    /** @test */
    public function it_doesnt_overwrite_existing_fragments()
    {
        $fragment = new Fragment([
            'group' => 'fragment',
            'key' => 'first',
            'hidden' => false,
            'contains_html' => false,
            'draft' => false,
        ]);

        $fragment->setTranslation('nl', 'Initial value');
        $fragment->save();

        $this->performImport();

        $newFragment = $this->getFragment('fragment', 'first');

        $this->assertEquals('Initial value', $newFragment->getTranslation('nl'));
    }

    /** @test */
    public function it_overwrites_existing_fragments_if_the_update_flag_is_set()
    {
        $fragment = new Fragment([
            'group' => 'fragment',
            'key' => 'first',
            'hidden' => false,
            'contains_html' => false,
            'draft' => false,
        ]);
        $fragment->setTranslation('nl', 'Hallo');
        $fragment->save();

        $this->performImport(true);

        $newFragment = $this->getFragment('fragment', 'first');

        $this->assertEquals('Een', $newFragment->getTranslation('nl'));
    }

    protected function performImport(bool $updateExistingFragments = false)
    {
        $importer = app(Importer::class);

        if ($updateExistingFragments) {
            $importer->updateExistingFragments();
        }

        $importer->import(__DIR__ . '/fixtures/fragments.xlsx');
    }

    /**
     * @param string $group
     * @param string $key
     * @return \App\Models\Fragment|null
     */
    protected function getFragment(string $group, string $key)
    {
        return Fragment::where('group', $group)
            ->where('key', $key)
            ->first();
    }
}
