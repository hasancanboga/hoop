<?php

namespace App\Services;

use Aws\CommandInterface;
use Aws\CommandPool;
use Aws\Exception\AwsException;
use Aws\Rekognition\RekognitionClient;
use Aws\ResultInterface;
use Exception;
use Illuminate\Http\UploadedFile;

class RekognitionService
{
    protected RekognitionClient $client;
    protected array $categories = [
        "Explicit Nudity",
        "Violence",
        "Visually Disturbing"
    ];

    public function __construct(protected array|UploadedFile $files)
    {
        $this->client = new RekognitionClient(config('aws'));

        if (!is_array($files)) {
            $this->files = [$files];
        }
    }

    public function rekognize(): mixed
    {
        $pool = $this->getCommandPool($this->getCommands());

        try {
            $pool->promise()->wait();
        } catch (Exception $e) {
            return $e->data ?? "Connection Error";
        }
        return false;
    }

    public function getCommands(): array
    {
        $commands = [];

        foreach ($this->files as $file) {
            $commands[] = $this->client->getCommand('DetectModerationLabels', [
                'Image' => [
                    'Bytes' => is_a($file, UploadedFile::class)
                        ? file_get_contents($file)
                        : $file
                ],
                'MinConfidence' => 30,
            ]);
        }

        return $commands;
    }

    private function getCommandPool(array $commands): CommandPool
    {
        $topLevelCategories = $this->categories;
        return new CommandPool($this->client, $commands, [
            'concurrency' => 5,
            'before' => function (CommandInterface $cmd) {
                gc_collect_cycles();
            },
            'fulfilled' => function (ResultInterface $result) use ($topLevelCategories) {
                $modLabels = $result->toArray()["ModerationLabels"];
                foreach ($modLabels as $modLabel) {
                    if (in_array($modLabel["ParentName"], $topLevelCategories) && $modLabel['Confidence'] >= 50) {
                        $e = new Exception();
                        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                        $e->data = $modLabel;
                        throw $e;
                    }
                }
            },
            'rejected' => function (AwsException $reason) {
                throw new Exception($reason);
            },
        ]);
    }


}
