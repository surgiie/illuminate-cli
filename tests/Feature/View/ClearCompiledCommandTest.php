<?php

beforeEach(function () {
    $this->compiledPath = illuminate_cli_workspace_path('compiled-views', 'ILLUMINATE_CLI_VIEW_COMPILED_PATH');

    if (! is_dir($this->compiledPath)) {
        mkdir($this->compiledPath, 0755, true);
    }
});

afterEach(function () {
    if (is_dir($this->compiledPath)) {
        array_map('unlink', glob("$this->compiledPath/*"));
    }
});

test('clears all compiled view files', function () {
    // Create some test compiled files
    file_put_contents($this->compiledPath.'/test1.php', '<?php echo "test"; ?>');
    file_put_contents($this->compiledPath.'/test2.php', '<?php echo "test"; ?>');

    expect(glob("$this->compiledPath/*.php"))->toHaveCount(2);

    $this->artisan('view:clear-compiled')->assertExitCode(0);

    expect(glob("$this->compiledPath/*.php"))->toBeEmpty();
});

test('clears only files older than specified minutes', function () {
    $oldFile = $this->compiledPath.'/old.php';
    $newFile = $this->compiledPath.'/new.php';

    // Create old file
    file_put_contents($oldFile, '<?php echo "old"; ?>');
    touch($oldFile, time() - 3600); // 1 hour old

    // Create new file
    file_put_contents($newFile, '<?php echo "new"; ?>');

    $this->artisan('view:clear-compiled', ['--minutes' => 30])
        ->assertExitCode(0);

    expect(file_exists($oldFile))->toBeFalse()
        ->and(file_exists($newFile))->toBeTrue();
});

test('validates minutes option is numeric', function () {
    $this->artisan('view:clear-compiled', ['--minutes' => 'invalid'])
        ->assertExitCode(1);
});

test('accepts null minutes option', function () {
    file_put_contents($this->compiledPath.'/test.php', '<?php echo "test"; ?>');

    $this->artisan('view:clear-compiled')
        ->assertExitCode(0);

    expect(glob("$this->compiledPath/*.php"))->toBeEmpty();
});
