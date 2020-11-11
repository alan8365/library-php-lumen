<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Code;
use App\Exceptions\Msg;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

const perPage = 10;

class BookController extends Controller
{

    /**
     * UserController constructor.
     * Auth middleware, exclude login and register.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['list', 'store', 'detail']]);
    }

    /**
     * @param Request $request
     * @return Response
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
        $book = (new Book)->find($isbn);
        $user = auth()->user();
        $isLike = $book->users()->where('email', '=', $user->email)->get()->count() > 0;

//        $books = Book::all()->leftJoin('', 'books.isbn', '=', 'users');
        return resp(Code::Success, Msg::Success, [
            'detail' => $book->first(),
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

        if ($isLiked){
            $user->books()->attach($isbn);
            return resp(Code::Success, Msg::SetFavoriteSuccess);
        }else{
            $user->books()->detach($isbn);
            return resp(Code::Success, Msg::UnsetFavoriteSuccess);
        }

    }
}
