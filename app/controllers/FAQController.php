<?php

class FAQController extends \BaseController {

    public function __construct()
    {

    }

    /**
     * Store a newly created question in DB.
     *
     * @return Response
     */
    public function storeQuestion()
    {

        $input = Input::all();

        $validator = Validator::make($input, 
            array(
                'question' => 'required|min:5|max:150',
                'prop_id' => 'required|numeric|exists:properties,id'
            )
        );

        if ($validator->fails())
        {
            return Redirect::back() //redirect back to form page with input and any errors
                ->withInput(Input::all())
                ->withErrors($validator);
        }

        $question = new FaqQuestion;
        
        $question->text = $input['question'];
        $question->prop_id = $input['prop_id'];
        $question->user_id = Sentry::getUser()->id;

        $question->save();

        return Redirect::back() //redirect back to form page with input and any errors
            ->withMessage('Thank you for submitting a question!');

    }

    /**
     * Display listing of questions
     *
     * @return Response
     */
    public function showQuestions($id)
    {

        $questions = FaqQuestion::where('prop_id', $id)->get(); //Get all questions for this property

        return View::make('dashboard.faq', compact('questions')); //Create view sending question data

    }

    /**
     * Store a newly created answer in DB.
     *
     * @return Response
     */
    public function storeAnswer()
    {

        $input = Input::all(); //Get all input from the form

        $validator = Validator::make($input, 
            array(
                'answer' => 'sometimes|required|min:5|max:200',
                'question_id' => 'required|numeric|exists:property_faq_questions,id',
                'public' => 'boolean'
            )
        );

        if ($validator->fails())
        {
            return Redirect::back() //redirect back to form page with input and any errors
                ->withInput(Input::all())
                ->withErrors($validator);
        }

        if(isset($input['answer'])){ // If no answer was set skip to update public status
            $answer = new FaqAnswer; //Create answer object and insert in to DB
            
            $answer->text = $input['answer'];
            $answer->question_id = $input['question_id'];

            $answer->save();
        }

        $question = FaqQuestion::find($input['question_id']); //Get question and set it to be public or not

        $question->public = ( isset($input['public']) ? 1 : 0 ); //if no public option was sent set it to zero else 1
        $question->read = 1;
        $question->save();


        return Redirect::back();

    }

    /**
     * Display a listing of questions
     *
     * @return Response
     */
    public function showAnswers()
    {

        $questions = FaqQuestion::where('user_id', Sentry::getUser()->id)->get(); //Get all questions with new answers that are unread for this user

        return View::make('account.faq', compact('questions')); //Create view sending question data

    }

}

