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
        $this->middleware('auth:api', ['except' => ['list', 'store']]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $perPage = intval($request->get('perPage'));

        $books = (new Book)->paginate($perPage);

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

        $attributes = [
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'publisher' => $publisher,
            'publication_date' => $publicationDate,
            'summary' => $summary
        ];

        $book = (new Book)->create($attributes);

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
        $books = $user->books()->get();

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
        $user->books()->attach([$isbn]);

        return resp(Code::Success, Msg::Success, $user);
    }
}
