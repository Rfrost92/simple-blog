<?php

namespace App\Tests;

use App\Utils\Slugifier;
use PHPUnit\Framework\TestCase;

final class SlugifierTest extends TestCase
{
    public function testSlugify(): void
    {
        $shortStringToSlug = 'short string to slug 12';
        $this->assertSame('short-string-to-slug-12', Slugifier::slugify($shortStringToSlug));

        $longStringToSlug = 'long string to slug 12 a really long string that should be slugged for real';
        $this->assertSame('long-string-to-slug-12-a-really-long-string-that-should-be-slugged-for-real', Slugifier::slugify($longStringToSlug));

        $emptyStringToSlug = '';
        $this->assertSame('', Slugifier::slugify($emptyStringToSlug));
    }
}