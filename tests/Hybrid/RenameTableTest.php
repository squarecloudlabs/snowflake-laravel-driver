<?php

namespace SingleStore\Laravel\Tests\Hybrid;

use Illuminate\Support\Facades\Schema;
use Snowflake\Laravel\Schema\Blueprint;
use SingleStore\Laravel\Tests\BaseTest;

class RenameTableTest extends BaseTest
{
    use HybridTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->runHybridIntegrations()) {
            $this->createTable(function (Blueprint $table) {
                $table->id();
            });
        }
    }

    protected function tearDown(): void
    {
        if ($this->runHybridIntegrations()) {
            Schema::dropIfExists('test_renamed');
        }

        parent::tearDown();
    }

    /** @test */
    public function rename_table()
    {
        if ($this->runHybridIntegrations()) {
            $cached = $this->mockDatabaseConnection;

            $this->mockDatabaseConnection = false;

            $this->assertFalse(Schema::hasTable('test_renamed'));
            Schema::rename('test', 'test_renamed');

            $this->assertTrue(Schema::hasTable('test_renamed'));

            $this->mockDatabaseConnection = $cached;
        }

        $blueprint = new Blueprint('test');
        $blueprint->rename('test_renamed');

        $statements = $blueprint->toSql($this->getConnection(), $this->getGrammar());

        $this->assertCount(1, $statements);
        $this->assertEquals('alter table `test` rename to `test_renamed`', $statements[0]);
    }
}
