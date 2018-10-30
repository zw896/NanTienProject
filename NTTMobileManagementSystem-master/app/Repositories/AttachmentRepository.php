<?php

namespace App\Repositories;


use Storage;

class AttachmentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model() {
        return \App\Models\Attachment::class;
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id) {
        $attachment = $this->find($id);

        $path = "";
        if ($attachment->type == 0) {
            $path = '/public/attachments/images/';
        }

        // delete file
        Storage::delete($path . $attachment->filename);

        return parent::delete($id);
    }
}
