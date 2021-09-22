<?php

namespace App\Services;

use App\Models\Media;
use Aws\Rekognition\RekognitionClient;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class RekognitionService
{
    protected RekognitionClient $client;
    protected array $categories = [
        "Explicit Nudity",
        "Violence",
        "Visually Disturbing"
    ];

    public function __construct(protected Media $image)
    {
        $this->client = new RekognitionClient(config('aws'));
    }

    /**
     * @throws FileNotFoundException
     */
    public function rekognize(): mixed
    {
        $result = $this->client->detectModerationLabels([
            'Image' => [
                'Bytes' => $this->image->getTempFile(),
            ],
            'MinConfidence' => 30,
        ]);

        $modLabels = $result->toArray()["ModerationLabels"];

        try {
            foreach ($modLabels as $modLabel) {
                if (in_array($modLabel["ParentName"], $this->categories) && $modLabel['Confidence'] >= 50) {
                    $e = new Exception();
                    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
                    $e->data = $modLabel;
                    throw $e;
                }
            }
        } catch (Exception $e) {
            return $e->data ?? "Connection Error";
        }

        return false;
    }
}
