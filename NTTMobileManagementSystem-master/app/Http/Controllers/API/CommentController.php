<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\Exceptions\IncorrectFileFormat;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use JSendResponse;
use Validator;

/**
 * Class CommentController
 * @package App\Http\Controllers\API
 */
class CommentController extends BaseController {
    /**
     * @param $eid
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComment($eid, Request $request) {
        $page = $this->getPage($request);

        try {
            $comments = Event::with([
                'comments' => function ($query) use ($page) {
                    $query->with('attachments');
                    $query->where('comments.display', '=', '1');
                    $query->orderBy('comments.created_at', 'desc');
                    $query->limit($this->entityPerPage);
                    $query->offset($this->entityPerPage * ($page - 1));
                }
            ])
                ->where('published', '=', '1')
                ->findOrFail($eid)
                ->comments;

            $total = Comment::where('eid', '=', $eid)
                ->where('display', '=', '1')
                ->count();

            return JSendResponse::success(200, ['comments' => $comments, 'hasNext' => $this->hasNextPage($page, $total), 'pageNum' => $page]);
        } catch (ModelNotFoundException $e) {
            return JSendResponse::fail(404, ['error' => 'Event not found']);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment($id, Request $request) {
        $comment = $request->get('comment');
        $rating = $request->get('rating');
        $uid = $this->guard()->id();

        $validator = Validator::make($request->all(),
            [
                'rating' => 'required|min:0|max:5|numeric',
                'comment' => 'required|string'
            ]
        );

        // validate
        if ($validator->fails()) {
            return JSendResponse::fail(400, ['error' => $validator->messages()]);
        }

        try {
            // create a transaction
            DB::transaction(function () use ($uid, $comment, $rating, $request, $id) {
                // check the it is the event exist
                Event::where('published', '=', '1')
                    ->where('id', '=', $id)
                    ->firstOrFail();

                $entity = Comment::create(['eid' => $id, 'uid' => $uid, 'content' => $comment, 'rating' => $rating, 'display' => false]);

                // support up to 9 images (optional)
                for ($i = 0; $i < 9; ++$i) {
                    if ($request->hasFile('image_' . $i)) {
                        $this->storeFile($request->file('image_' . $i), 0, $entity->id);
                    }
                }
            });

            return JSendResponse::success(201);
        } catch (ModelNotFoundException $e) {
            return JSendResponse::fail(404, ['error' => 'Event not found']);
        } catch (IncorrectFileFormat $e) {
            return JSendResponse::error(404, ['error' => 'Incorrect image format']);
        }
    }

    /**
     * @param UploadedFile $image
     * @param $id
     * @throws IncorrectFileFormat
     */
    private function storeFile(UploadedFile $image, $type, $id) {
        $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];

        if (in_array($image->getMimeType(), $allowedMimeTypes)) {
            $extension = $image->getClientOriginalExtension();
            $newFileName = str_random(32) . '.' . $extension;
            $image->storePubliclyAs('public/attachments/images', $newFileName);

            // create new entry
            Attachment::create(['cid' => $id, 'type' => $type, 'filename' => $newFileName, 'size' => $image->getSize()]);
        } else {
            throw new IncorrectFileFormat('expect image, got ' . $image->getMimeType());
        }
    }
}
