<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Search API for Book
     *
     * @param  $request Illuminate\Http\Request
     * @return json
     *
     */
    public function search(Request $request) 
    {
       $books = [];
       $query = $request->get('query');
       $books = Books::query();
        if (!empty($request->get('query'))) {
            $books = $books->where(function ($q) use ($query) {
                $q->where('title','like','%'.$query.'%')->orWhere('author', 'LIKE', '%'.$query.'%')->orWhere('genre', 'LIKE', '%'.$query.'%')->orWhere('isbn', 'LIKE', '%'.$query.'%');
            });
        }
        if ($request->get('selectedGenres')) {
            $books = $books->whereIn('genre',(array)$request->get('selectedGenres'));
        }
        if ($request->get('publishedFrom')) {
            $books = $books->whereDate('published','>=',$request->get('publishedFrom'));
        }
        if ($request->get('publishedTo')) {
            $books = $books->whereDate('published','<=',$request->get('publishedTo'));
        }
        if (!empty($request->get('orderBy'))) {
            if($request->get('orderBy') == 'recently_published') {
                $books = $books->orderBy('published','desc');
            } else {
                $books = $books->orderBy('title');
            }
        }
        $books = $books->paginate(10);
        return $this->success("Data fetched successfully.",$books);
    }

    /**
     * get List of Genres
     *
     * @return json
     *
     */
    public function genres() 
    {
        $genres =  Books::select('genre')->groupBy('genre')->get();
        return $this->success("Data fetched successfully.",$genres);
    }

    /**
     * get book by id
     *
     * @param $id INT
     * @return json
     *
     */
    public function product($id) 
    {   
        $book = Books::find($id);
        return $this->success("Data fetched successfully.",$book);
    }

    /**
     * get List of books
     *
     * @return json
     *
     */
    public function index()
    {
        $books = Books::orderBy('id','DESC')->paginate(10);
        return $this->success('Data fetched successfully',$books);
    }

     /**
     * store new book
     *
     * @param  $request Illuminate\Http\Request
     * @return json
     *
     */
    public function add(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'genre' => 'required',
            'description' => 'required',
            'isbn' => 'required|integer',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published' => 'required|date',
            'publisher' => 'required',
        ]);

        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'image/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
        }

        Books::create($input);
        return $this->success("Book created successfully");
    }

     /**
     * store new book
     *
     * @param  $request Illuminate\Http\Request
     * @return json
     *
     */
    public function edit($id)
    {
        $book = Books::find($id);
        return $this->success("data fetched sucessfully.",$book);
    }

    /**
     * update book by Id
     *
     * @param  $id INT
     * @param  $request Illuminate\Http\Request
     * @return json
     *
     */
    public function update($id, Request $request)
    {
        $book = Books::find($id);
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'genre' => 'required',
            'description' => 'required',
            'isbn' => 'required|integer',
            'file' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'published' => 'required|date',
            'publisher' => 'required',
        ]);

        $input = $request->all();
        $imageName = NULL;
        if ($image = $request->file('file')) {
            $destinationPath = 'image/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
            unlink('image/'.$book->image);
        }  
        $book->update($input);
        return $this->success("Book update successfully");
    }

    /**
     * delete book by Id
     *
     * @param  $id INT
     * @return json
     *
     */
    public function delete($id)
    {
        $book = Books::find($id);
        $book->delete();
        unlink('image/'.$book->image);
        return $this->success("Book deleted successfully");
    }
}
