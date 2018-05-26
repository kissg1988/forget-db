<?php

namespace Tests\Unit;

use App\Services\UtilityService;
use League\Flysystem\Util;
use Symfony\Component\Yaml\Yaml;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class UtilityTest extends TestCase
{
    public function test_i_can_clean_paths()
    {
        $this->assertNotEmpty(UtilityService::cleansePath(''));
        $this->assertNotEmpty(UtilityService::cleansePath('./'));
        $this->assertNotEmpty(UtilityService::cleansePath('.'));
        $this->assertNotEmpty(UtilityService::cleansePath('/lemonade/'));
        $this->assertStringEndsNotWith('/', UtilityService::cleansePath('/lemonade/'));
    }

    public function test_i_can_create_folders()
    {
        // Existing path
        $this->assertEquals(getcwd(), UtilityService::createFolderForFile(''));

        // New folder
        $newPath = UtilityService::createFolderForFile('tmp/hello.txt');
        $this->assertFileExists('tmp');
        rmdir('tmp');
    }

    public function test_i_can_cleanse_filename()
    {
        $name = UtilityService::cleanseFilename('&^7d jdsf .cake');

        $this->assertStringEndsWith('.yml', $name);
        $this->assertEquals('7d-jdsf.yml', $name);
    }

    public function test_i_can_get_sub_config()
    {
        $yml = UtilityService::stubConfig();
        $config = Yaml::parse($yml);

        $this->assertCount(3, $config);
        $this->assertArrayHasKey('key', current($config));
        $this->assertArrayHasKey('conditions', current($config));
        $this->assertArrayHasKey('columns', current($config));
    }

    public function test_i_can_get_branded_message()
    {
        $message = UtilityService::message('hello');

        $this->assertStringEndsWith('hello', $message);
        $this->assertContains('forget-db', $message);
    }
}