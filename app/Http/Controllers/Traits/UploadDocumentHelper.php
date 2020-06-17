<?php

namespace Bpocallaghan\Titan\Http\Controllers\Traits;

use Illuminate\Validation\ValidationException;

trait UploadDocumentHelper
{
    /**
     * Generate a filename and try to move the file
     * @param $attributes
     * @return string
     * @throws ValidationException
     */
    protected function uploadDocument(&$attributes)
    {
        $filename = "";
        if (request()->hasFile('file')) {

            // get and move file
            $file = $attributes['file'];
            $filename = token() . '.' . $file->extension();
            $file->move(upload_path('documents'), $filename);
            unset($attributes['file']); // remove from attributes

            if (!\File::exists(upload_path('documents') . $filename)) {
                $validator = \Validator::make([], ['file' => 'required'],
                    ['file.required' => 'Something went wrong, we could not upload the file. Please try again.']);

                throw new ValidationException($validator);
            }
        }

        return $filename;
    }

    /**
     * Upload and save the document to resource
     * @param $resource
     * @param $name
     * @param $filename
     */
    protected function createOrUpdateDocument($resource, $name, $filename)
    {
        if (request()->hasFile('file')) {

            // create or update
            if (is_null($resource->document)) {
                $resource->documents()->create([
                    'name'     => $name,
                    'filename' => $filename,
                ]);
            }
            else {
                // update the document entry
                $resource->document->update([
                    'name'     => $name,
                    'filename' => $filename,
                ]);
            }
        }
    }
}