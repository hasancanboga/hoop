<?php

namespace App\Services;

use App\Models\Media;
use Exception;
use Illuminate\Support\Facades\Storage;

class VideoService
{

    public function __construct(protected Media $video)
    {
        //
    }

    /**
     * @throws Exception
     */
    public function store()
    {
        // $this->rekognize();

        $fileName = $this->video->collection . '/' . $this->video->id . '.' . $this->video->getTempFileExtension();

        Storage::put(
            $fileName,
            Storage::disk('local')->get($this->video->temp_file_name)
        );

        $this->video->file_name = $fileName;
        $this->video->mime_type = Storage::disk('local')
            ->mimeType($this->video->temp_file_name);
        $this->video->save();

        Storage::disk('local')->delete($this->video->temp_file_name);
    }


    public function rollback()
    {
        Storage::delete($this->video->file_name);
        Storage::disk('local')->delete($this->video->temp_file_name);
    }

    /**
     * @throws Exception
     */
    public function rekognize(): void
    {
        // look at StartContentModeration and GetContentModeration
        // instead of DetectModerationLabels
        $rekognition = new RekognitionService($this->video);
        if ($rekognize = $rekognition->rekognize()) {
            $this->rollback();
            if ($rekognize == 'Connection Error') {
                throw new Exception(__('misc.unknown_error'));
            } else {
                throw new Exception(__('misc.rekognition_failure', [
                    'reason' => __($rekognize['ParentName'])
                ]));
            }
        }
    }

}
