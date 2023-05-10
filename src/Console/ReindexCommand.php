<?php

declare(strict_types=1);

/*
 * This file is part of the Schranz Search package.
 *
 * (c) Alexander Schranz <alexander@sulu.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Schranz\Search\Integration\Laravel\Console;

use Illuminate\Console\Command;
use Schranz\Search\SEAL\EngineRegistry;
use Schranz\Search\SEAL\Reindex\ReindexProviderInterface;

/**
 * @experimental
 */
final class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schranz:search:reindex {--engine= : The name of the engine} {--index= : The name of the index} {--drop : Drop the index before reindexing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex configured search indexes.';

    /**
     * @param iterable<ReindexProviderInterface> $reindexProviders
     */
    public function __construct(
        private readonly iterable $reindexProviders, // TODO move to handle method: https://discord.com/channels/297040613688475649/1105593000664498336
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(
        EngineRegistry $engineRegistry,
    ): int {
        /** @var string|null $engineName */
        $engineName = $this->option('engine');
        /** @var string|null $indexName */
        $indexName = $this->option('index');
        /** @var bool $drop */
        $drop = $this->option('drop');

        foreach ($engineRegistry->getEngines() as $name => $engine) {
            if ($engineName && $engineName !== $name) {
                continue;
            }

            $this->line('Engine: ' . $name);

            $progressBar = $this->output->createProgressBar();

            $engine->reindex(
                $this->reindexProviders,
                $indexName,
                $drop,
                function (string $index, int $count, ?int $total) use ($progressBar) {
                    $progressBar->setMessage($index);
                    $progressBar->setProgress($count);

                    if (null !== $total) {
                        $progressBar->setMaxSteps($total);
                    }
                },
            );

            $progressBar->finish();
            $this->line('');
            $this->line('');
        }

        $this->info('Search indexes reindexed.');

        return 0;
    }
}
