<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\Code;
use App\Exceptions\Msg;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use phpDocumentor\Reflection\Types\True_;

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
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        // TODO perPage uniform
        $books = (new Book)->paginate(10);

//        $books = Book::all()->leftJoin('', 'books.isbn', '=', 'users');
        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return JsonResponse
     */
    public function detail(Request $request, string $isbn)
    {
        // TODO perPage uniform
        $books = Book::find($isbn);

//        $books = Book::all()->leftJoin('', 'books.isbn', '=', 'users');
        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @return JsonResponse
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
     * @return JsonResponse
     */
    public function listFavorite(Request $request)
    {
        $perPage = intval($request->get('perPage'));

        $user = auth()->user();

        // TODO perPage uniform
        $books = $user->books()->paginate(10);

        return resp(Code::Success, Msg::Success, $books);
    }

    /**
     * @param Request $request
     * @param string $isbn
     * @return JsonResponse
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
