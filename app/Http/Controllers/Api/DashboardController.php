<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Code;
use App\Exceptions\Msg;
use App\Http\Controllers\Controller;
use App\Models\Book;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Post(
     *     path="/dashboard/book",
     *     summary="Create new book.",
     *     tags={"Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="isbn",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="publisher",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="publication_date",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="summary",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="img_src",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Field invalid."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     ),
     * )
     */
    public function storeBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books',
            'title' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'publication_date' => 'required|date',
            'summary' => 'required',
        ], [
            '*.required' => ':attribute is required.',
            'isbn.unique' => 'Book already exist.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::Failed, $error[0]);
            }
        }

        $book = Book::create($request->all());

        return resp(Code::Success, Msg::Success, $book);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *     path="/dashboard/book",
     *     summary="List all book",
     *     tags={"Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="list success"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     )
     * )
     */
    public function getAllBook(Request $request)
    {
        $books = Book::all();

        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     *
     * @OA\Put(
     *     path="/dashboard/book/{isbn}",
     *     summary="Update target book.",
     *     tags={"Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="isbn",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="publisher",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="publication_date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="summary",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="img_src",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update success"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Field invalid."
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     ),
     * )
     */
    public function updateBook(Request $request, string $isbn)
    {
        $book = Book::find($isbn);

        $updated = $book->update($request->all());

        if ($updated) {
            return resp(Code::Success, Msg::Success, $book);
        } else {
            return resp(Code::Failed, Msg::Failed, $book);
        }
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     * @throws Exception
     *
     * @OA\Delete(
     *     path="/dashboard/book/{isbn}",
     *     summary="Remove target book.",
     *     tags={"Dashboard"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="isbn",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delete success"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found."
     *     )
     * )
     */
    public function removeBook(Request $request, string $isbn)
    {
        $validator = Validator::make(["isbn" => $isbn], [
            'isbn' => 'exists:books',
        ], [
            'isbn.exists' => 'The isbn is not in database.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::NotFound, Msg::BookNotFound);
            }
        }

        $book = Book::find($isbn);

        $book->delete();

        return resp(Code::Success, Msg::Success);
    }
}
