<?php

namespace App\Service\FireBase;

use Kreait\Firebase\Factory;


class FirebaseStorageService
{
    protected $storage;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.service_account'));
        $this->storage = $factory->createStorage();
    }


    /**
     * @param $file
     * @param $mediaType
     * @param $path
     * @return string|void
     * @throws \Exception
     */
    public function handle($file, $mediaType, $path)
    {
        try {
            // Generate a unique file path for the upload
            $filePath = $this->generateFilePath($file, $mediaType, $path);

            // Log bucket name for debugging purposes
            $this->logBucketName();

            // Upload the file to Firebase Storage
            $this->uploadToFirebase($file, $filePath);

            // Get the signed URL for the uploaded file
            return $this->getSignedUrl($filePath);

        } catch (\Exception $exception) {
            // Log the error and throw a custom exception
            $this->handleUploadError($exception);
        }
    }

    /**
     * Generate a unique file path based on media type and file extension.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $mediaType
     * @return string
     */
    private function generateFilePath($file, $mediaType, $path)
    {
        $extension = $file->getClientOriginalExtension();
        return sprintf($path,'/%s/%s.%s', $mediaType, uniqid(), $extension);
    }

    /**
     * Log the bucket name for debugging.
     *
     * @return void
     */
    private function logBucketName()
    {
        $bucket = $this->storage->getBucket();
        app('log')->info('Bucket name: ', ['name' => $bucket->name()]);
    }

    /**
     * Upload the file to Firebase Storage.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $filePath
     * @return void
     */
    private function uploadToFirebase($file, $filePath)
    {
        $bucket = $this->storage->getBucket();
        $bucket->upload(fopen($file->getRealPath(), 'r'), ['name' => $filePath]);
    }

    /**
     * Get a signed URL for the uploaded file.
     *
     * @param  string  $filePath
     * @return string
     */
    private function getSignedUrl($filePath)
    {
        $bucket = $this->storage->getBucket();
        $fileReference = $bucket->object($filePath);
        return $fileReference->signedUrl(new \DateTime('1 hour'));
    }

    /**
     * Handle any errors that occur during file upload.
     *
     * @param  \Exception  $exception
     * @throws \Exception
     */
    private function handleUploadError($exception)
    {
        app('log')->error('Unexpected error:[uploadFile] ' . $exception->getMessage());
        throw new \Exception(__('messages.unexpected_error'));
    }
}
