<?php

class UploadController extends \BaseController {


    public function __construct(Upload $upload)
    {

        $filtered = array('update', 'destroy');
        $id = Route::current()->getParameter('id');

        $this->beforeFilter('ownerAuth:' . 'Upload-'.$id, array('only' => $filtered));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return View::make('test.form');

    }


    /**
     * Store a newly created upload in storage and DB.
     *
     * @return Response
     */
    public function store()
    {

            $upload = new Upload;

            $upload->media = Input::file('file');
            $upload->user_id = Sentry::getUser()->id;
            $upload->save();

            if($upload->id)
            {
                $thumburl = URL::asset($upload->media->url('thumb').$upload->media->filename);
                $fullsizeurl = URL::asset($upload->media->url().$upload->media->filename);

                // this creates the response structure for jquery file upload
                $success = new stdClass();
                $success->name = $upload->media->originalFilename();
                $success->size = $upload->media->size();
                $success->url = $fullsizeurl;
                $success->thumbnailUrl = $thumburl;
                $success->deleteUrl = action('UploadController@destroy', $upload->id);
                $success->deleteType = 'DELETE';
                $success->fileID = $upload->id;

                return Response::json(array( 'files'=> array($success)), 200);

            } else {
                return Response::json('Error', 400);
            }

    }


    /**
     * Remove the specified upload from storage and DB.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        
        $upload = Upload::find($id);
        $upload->delete();

        $success = new stdClass();
        $success->{$upload->media->originalFilename()} = true;

        return Response::json(array('files'=> array($success)), 200);    

    }

}

