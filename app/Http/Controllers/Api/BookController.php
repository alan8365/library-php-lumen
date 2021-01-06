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
     *     path="book/",
     *     @OA\Response(
     *         response="default",
     *         description="HIHI"
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
     */
    public function detail(Request $request, string $isbn)
    {
        $book = Book::where('isbn', '=', $isbn)->first();
        $user = auth()->user();

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
     */
    public function listFavorite(Request $request)
    {
        // $perPage = intval($request->get('perPage'));
        $user = auth()->user();

        // TODO perPage uniform
        $books = $user->books()->paginate(perPage);

        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return Response
     */
    public function setFavorite(Request $request, string $isbn)
    {
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
     *     path="book/search",
     *     summary="search book by title",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"title"},
     *                 @OA\Property(property="title", type="string", description="title of book"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     ),
     * )
     *
     * @param Request $request
     * @return Response
     *
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
