<?php

namespace App\Http\Controllers\Api;


use App\Exceptions\Code;
use App\Exceptions\Msg;
use App\Http\Validator\searchValidator;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\RequestBody;

const perPage = 10;

class BookController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *     path="/book",
     *     summary="List all posts by paginate",
     *     tags={"Book"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="int",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="list success"
     *     )
     * )
     */
    public function list(Request $request)
    {
        $books = (new Book)->paginate(perPage);

        // $books = Book::all()->leftJoin('', 'books.isbn', '=', 'users');
        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     *
     * @OA\Get(
     *     path="/book/{isbn}",
     *     summary="Show book detail",
     *     tags={"Book"},
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
     *         description="Get detail success."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found."
     *     )
     * )
     */
    public function detail(Request $request, string $isbn)
    {
        $book = Book::where('isbn', '=', $isbn)->first();
        $user = auth()->user();

        if (!$book) {
            return resp(Code::NotFound, Msg::BookNotFound);
        }

        if ($user) {
            $isLike = $book->users()->where('email', '=', $user->email)->get()->count() > 0;
        } else {
            $isLike = false;
        }

//        $books = Book::all()->leftJoin('', 'books.isbn', '=', 'users');
        return resp(Code::Success, Msg::Success, [
            'detail' => $book,
            'isLike' => $isLike
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO add validator
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books'
        ], [
            'isbn.unique' => 'Book already exist.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::Failed, $error[0]);
            }
        }

        $isbn = $request->get('isbn');
        $title = $request->get('title');
        $author = $request->get('author');
        $publisher = $request->get('publisher');
        $publicationDate = $request->get('publication_date');
        $summary = $request->get('summary');
        $imgSrc = $request->get('img_src');

        $attributes = [
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'publisher' => $publisher,
            'publication_date' => $publicationDate,
            'img_src' => $imgSrc,
            'summary' => $summary,
        ];
        error_log(print_r($attributes, true));

        $book = Book::create($attributes);

        return resp(Code::Success, Msg::Success, $book);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @OA\Get(
     *     path="/book/favorite",
     *     summary="Show all favorite book.",
     *     tags={"Book"},
     *     security={{"bearerAuth":{}}},
     *     @OA\SecurityScheme(
     *         securityScheme="bearerAuth",
     *         in="header",
     *         name="bearerAuth",
     *         type="http",
     *         scheme="bearer",
     *         bearerFormat="JWT",
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="int",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Get favorite book success."
     *     ),
     * )
     */
    public function listFavorite(Request $request)
    {
        $user = auth()->user();

        // TODO perPage uniform
        $books = $user->books()->paginate(perPage);

        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     *
     * @OA\Post(
     *     path="/book/favorite/{isbn}",
     *     summary="Chage book favorite status.",
     *     tags={"Book"},
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
     *         description="Change book favorite status success."
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found."
     *     )
     * )
     */
    public function setFavorite(Request $request, string $isbn)
    {
        $validator = Validator::make(["isbn" => $isbn], [
            'isbn' => 'exists:books',
        ], [
            'isbn.exists' => 'isbn is not in database.'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::NotFound, Msg::BookNotFound);
            }
        }

        $user = auth()->user();

        $isLiked = $user->books()->where('isbn', '=', $isbn)->count() < 1;

        if ($isLiked) {
            $user->books()->attach($isbn);
            return resp(Code::Success, Msg::SetFavoriteSuccess);
        } else {
            $user->books()->detach($isbn);
            return resp(Code::Success, Msg::UnsetFavoriteSuccess);
        }

    }

    /**
     * @OA\Get(
     *     path="/book/search",
     *     summary="Search book by title",
     *     tags={"Book"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="int",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     ),
     * )
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ], [
            'title.required' => 'Please input title',
            'title.max' => 'title at most :max characters',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $error) {
                return resp(Code::Failed, $error[0]);
            }
        }

        ['title' => $title] = $request->all();

        $books = Book::searchBook($title)->paginate(perPage);

        if ($books->total() <= 0) {
            return resp(Code::NotFound, Msg::BookNotFound);
        }

        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     * @throws \Exception
     */
    public function remove(Request $request, string $isbn)
    {
        $book = Book::find($isbn);

        $book->delete();

        return resp(Code::Success, Msg::Success);
    }
}
