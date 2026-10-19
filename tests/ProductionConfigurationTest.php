<?php

declare(strict_types=1);

namespace Tests;

use Crab\App;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ProductionConfigurationTest extends TestCase
{
    /**
     * @return iterable<string, array{string, string}>
     */
    public static function productionExamples(): iterable
    {
        $framework = dirname(new \ReflectionClass(App::class)->getFileName(), 2);
        yield 'skeleton' => [dirname(__DIR__) . '/README.md', '## In production'];
        yield 'integrated workers' => [$framework . '/docs/deployment.md', '## The other processes'];
        yield 'deploy recipe' => [$framework . '/docs/deployment.md', '## A deploy, start to finish'];
    }

    #[DataProvider('productionExamples')]
    public function testProductionSettingsGiveCommandsAndJobsThePublicUrl(string $page, string $heading): void
    {
        $directory = sys_get_temp_dir() . '/crab-production-settings-' . bin2hex(random_bytes(6));
        mkdir($directory . '/config', recursive: true);
        try {
            $markdown = file_get_contents($page);
            $section = substr($markdown, strpos($markdown, $heading));
            preg_match('/(?:```|~~~)text\n(.*?)(?:```|~~~)/s', $section, $match);
            self::assertArrayHasKey(1, $match, $page);
            copy(dirname(__DIR__) . '/config/app.php', $directory . '/config/app.php');
            file_put_contents(
                $directory . '/.env',
                file_get_contents(dirname(__DIR__) . '/.env.example') . "\n" . $match[1],
            );
            $autoload = var_export(dirname(__DIR__) . '/vendor/autoload.php', true);
            $probe = <<<'PHP'
                $app = Crab\App::configure(__DIR__)->withProviders(only: [])->create();
                $app->router->get('/orders/{id}', static fn(int $id): array => ['id' => $id])->name('orders.show');
                echo $app->container->get(Crab\Contracts\Routing\Urls::class)->to('orders.show', ['id' => 7]);
                PHP;
            file_put_contents($directory . '/probe.php', "<?php\nrequire {$autoload};\n" . $probe);
            $environment = getenv();
            unset($environment['APP_URL']);
            $process = proc_open(
                [PHP_BINARY, $directory . '/probe.php'],
                [1 => ['pipe', 'w'], 2 => ['pipe', 'w']],
                $pipes,
                $directory,
                $environment,
            );
            self::assertIsResource($process);
            $output = stream_get_contents($pipes[1]);
            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            self::assertSame(0, proc_close($process), $error);
            self::assertSame('https://api.example.com/orders/7', $output);
        } finally {
            foreach (glob($directory . '/config/*') as $file) {
                unlink($file);
            }
            rmdir($directory . '/config');
            foreach (glob($directory . '/*') as $file) {
                unlink($file);
            }
            if (is_file($directory . '/.env')) {
                unlink($directory . '/.env');
            }
            rmdir($directory);
        }
    }

    public function testTheWorkerEnvironmentIsNotTruncatedBeforeCommandValidation(): void
    {
        $previous = getenv('SERVER_WORKERS');
        try {
            putenv('SERVER_WORKERS=2workers');
            $settings = require dirname(__DIR__) . '/config/server.php';
            $app = new App(new \Crab\Config\Config(['server' => $settings]));
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('--workers=4');
            \Crab\Commands\Serve::workers($app, new \Crab\Console\Input());
        } finally {
            putenv($previous === false ? 'SERVER_WORKERS' : 'SERVER_WORKERS=' . $previous);
        }
    }
}
