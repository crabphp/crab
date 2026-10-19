<?php

declare(strict_types=1);

namespace Tests;

use Crab\App;
use Crab\Cache\ArrayStore;
use Crab\Contracts\Cache\Cache;
use Crab\Contracts\Cache\Store;
use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    private string $directory;
    private string|false $cacheStore;

    protected function setUp(): void
    {
        $this->directory = sys_get_temp_dir() . '/crab-skeleton-' . bin2hex(random_bytes(6));
        mkdir($this->directory);
        $this->cacheStore = getenv('CACHE_STORE');
        putenv('CACHE_STORE=file');
    }

    protected function tearDown(): void
    {
        putenv($this->cacheStore === false ? 'CACHE_STORE' : 'CACHE_STORE=' . $this->cacheStore);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($this->directory);
    }

    public function testApplicationsCannotReadOrClearEachOthersCache(): void
    {
        $first = $this->application('first');
        $second = $this->application('second');
        $one = $first->container->get(Cache::class);
        $two = $second->container->get(Cache::class);
        $key = 'skeleton.' . bin2hex(random_bytes(6));
        try {
            $one->set($key, 'first');
            self::assertNull($two->get($key));
            $two->set($key, 'second');
            $two->clear();
            self::assertSame('first', $one->get($key));
        } finally {
            $one->delete($key);
            $two->delete($key);
        }
    }

    public function testTheDocumentedCacheEnvironmentIsIsolatedPerApplication(): void
    {
        putenv('CACHE_STORE=array');
        $first = $this->application('first');
        self::assertInstanceOf(ArrayStore::class, $first->container->get(Store::class));
        $first->container->get(Cache::class)->set('rates', 'first');
        $second = $this->application('second');
        self::assertNull($second->container->get(Cache::class)->get('rates'));
    }

    public function testABootstrapFailureExplainsHowToRecoverServerControl(): void
    {
        $this->application('broken');
        $path = $this->directory . '/broken/routes/web.php';
        file_put_contents($path, '<?php syntax error here');
        foreach (['reload', 'status', 'stop', '--help'] as $command) {
            [$status, $output, $error] = $this->console('broken', [$command]);
            self::assertSame(1, $status);
            self::assertSame('', $output);
            self::assertStringContainsString($path, $error);
            self::assertStringContainsString('did not contact the running server', $error);
            self::assertStringContainsString('Restore the last working application files', $error);
        }
        copy(dirname(__DIR__) . '/routes/web.php', $path);
        [$status, , $error] = $this->console('broken', ['stop']);
        self::assertSame(0, $status, $error);
    }

    public function testTheExampleCommandListsDescribesAndGreetsByItsNewName(): void
    {
        $this->application('commands');
        [$status, $output, $error] = $this->console('commands', []);
        self::assertSame(0, $status, $error);
        self::assertStringContainsString('hello:say', $output);
        [$status, $output, $error] = $this->console('commands', ['hello:say', 'Ada']);
        self::assertSame([0, "Hello, Ada!\n", ''], [$status, $output, $error]);
        [$status, $output, $error] = $this->console('commands', ['hello:say', '--help']);
        self::assertSame(0, $status, $error);
        self::assertStringContainsString('php cua hello:say [name]', $output);
        self::assertSame(1, $this->console('commands', ['unknown:command'])[0]);
    }

    private function application(string $name): App
    {
        $base = $this->directory . '/' . $name;
        foreach (['config', 'routes', 'vendor'] as $folder) {
            mkdir($base . '/' . $folder, recursive: true);
        }
        foreach ([
            'app.php',
            'cua',
            ...array_map(
                static fn(string $file): string => 'config/' . basename($file),
                glob(dirname(__DIR__) . '/config/*.php'),
            ),
            'routes/web.php',
            'routes/console.php',
        ] as $file) {
            copy(dirname(__DIR__) . '/' . $file, $base . '/' . $file);
        }
        $autoload = var_export(dirname(__DIR__) . '/vendor/autoload.php', true);
        file_put_contents($base . '/vendor/autoload.php', "<?php\nrequire {$autoload};\n");
        $app = require $base . '/app.php';
        return $app;
    }

    /**
     * @param list<string> $arguments
     * @return array{int, string, string}
     */
    private function console(string $name, array $arguments): array
    {
        $process = proc_open(
            [PHP_BINARY, $this->directory . '/' . $name . '/cua', ...$arguments],
            [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
            $pipes,
        );
        self::assertIsResource($process);
        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        return [proc_close($process), $output, $error];
    }
}
