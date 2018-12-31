<?php

namespace App\Http\Controllers;

use App\Answer;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Question;

class AnswersController extends Controller
{
    public function store(Question $question, Request $request)
    {
        $request->validate([
            'body' => 'required'
        ]);
        $question->answers()->create(['body' => $request->body,
            'user_id' => \Auth::id()]);
        return back()->with('success', 'Comment submited');
    }

    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        return view('answers.edit', compact('question', 'answer'));
    }

    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);
        $answer->update($request->validate([
            'body' => 'required',
        ]));
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Updated',
                'body_html' => $answer->body_html
            ]);
        }
        return redirect()->route('questions.show', $question->slug)->with('success', 'Your answer has been updated');
    }

    public function destroy(Question $question, Answer $answer)
    {
        $this->authorize('delete', $answer);
        $answer->delete();
        if (request()->expectsJson()) {
            return response()->json([
              'message'=>'Deleted!'
            ]);
        }
        return back()->with('success', 'Deleted!');
    }
}
