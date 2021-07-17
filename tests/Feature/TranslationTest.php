<?php

namespace Tests\Feature;

use Tests\TestCase;
use DirectoryIterator;
use Illuminate\Support\Facades\Lang;

class TranslationTest extends TestCase
{
    public function test_language_files_have_equal_number_of_keys()
    {
        $langs = [];
        foreach (new DirectoryIterator(resource_path('lang')) as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $langs[] = $fileinfo->getFilename();
            }
        }

        $i = 0;
        foreach ($langs as $lang) {
            if (++$i != count($langs)) {
                foreach (new DirectoryIterator(resource_path("lang/" . $lang)) as $fileinfo) {
                    if ($fileinfo->isFile()) {

                        $currentLangFileSize = count(
                            Lang::get($fileinfo->getBasename('.php'), [], $lang)
                        );

                        $nextLangFileSize = count(
                            Lang::get($fileinfo->getBasename('.php'), [], $langs[$i])
                        );

                        $this->assertEquals(
                            $currentLangFileSize,
                            $nextLangFileSize,
                            "WARNING: '" . $fileinfo->getBasename('.php') . "' LANGUAGE FILES HAS UNEQUAL NUMBER OF TRANSLATIONS."
                        );

                        if ($fileinfo->getBasename('.php') == 'validation') {
                            $currentLangValidationAttributesSize = count(
                                Lang::get($fileinfo->getBasename('.php'), [], $lang)['attributes']
                            );

                            $nextLangValidationAttributesSize = count(
                                Lang::get($fileinfo->getBasename('.php'), [], $langs[$i])['attributes']
                            );
                            $this->assertEquals(
                                $currentLangValidationAttributesSize,
                                $nextLangValidationAttributesSize,
                                "WARNING: 'validation.attributes' LANGUAGE FILES HAS UNEQUAL NUMBER OF TRANSLATIONS."
                            );
                        }
                    }
                }
            }
        }
    }
}
