<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Management</title>
    @include('admin.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<body>
@include('admin.header')
<div class="d-flex align-items-stretch">
    @include('admin.sidebar')
    <div class="page-content">
        <div class="container-fluid">
            <h1 class="mb-4">Book Management</h1>

            <!-- Add Book Button -->
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addBookModal">Add Book</button>
            
            <!-- Search Form -->
                <form action="{{ route('books.index') }}" method="GET" class="form-inline mb-4">
                     <input type="text" name="search" class="form-control" placeholder="Search by title or author" value="{{ request()->get('search') }}">
                     <button type="submit" class="btn btn-primary ml-2">Search</button>
                </form>



            <!-- Books Table -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Author Image</th>
                    <th>Book Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author_name }}</td>
                        
                        <td>{{ $book->quantity }}</td>
                        <td>{{ $book->description }}</td>
                        <td>{{ $book->category->cat_title }}</td>

                          <td>
                            <img class="img_author" src="{{ asset('storage/' . $book->author_img) }}" style="width: 60px; height: 60px;">
                        </td>
                        <td>
                            <img class="img_book" src="{{ asset('storage/' . $book->book_img) }}" style="width: 60px; height: 60px;">
                        </td>
                        

                        
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#editBookModal{{ $book->id }}">Edit</button>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?');">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Book Modal -->
                    <div class="modal fade" id="editBookModal{{ $book->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Book</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Edit Form Fields -->
                                        <div class="form-group">
                                            <label>Book Title</label>
                                            <input type="text" name="title" class="form-control" value="{{ $book->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Author</label>
                                            <input type="text" name="author_name" class="form-control" value="{{ $book->author_name }}" required>
                                        </div>
                                       
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" class="form-control" value="{{ $book->quantity }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" required>{{ $book->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select name="category_id" class="form-control" required>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->cat_title }}
                                                    </option>
                `                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label>Author Image</label>
                                            <input type="file" name="author_img" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Book Image</label>
                                            <input type="file" name="book_img" class="form-control">
                                        </div> --}}

                                        <div class="form-group">
                                            <label>Current Author Image</label>
                                            <img style="width:80px; margin:auto;"src="{{ asset('storage/' . $book->author_img) }}" >
                                        </div>
                
                                        <div class="form-group">
                                            <label>Change Author Image</label>
                                            <input type="file" name="author_img">
                                        </div>
                
                                        <div class="form-group">
                                            <label>Current Book Image</label>
                                            <img style="width:80px;  margin:auto;" src="{{ asset('storage/' . $book->book_img) }}"  >
                                        </div>
                
                                        <div class="form-group">
                                            <label>Change Book Image</label>
                                            <input type="file" name="book_img">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $books->links() }}
            </div>

            <!-- Add Book Modal -->
            <div class="modal fade" id="addBookModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Add Book</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <!-- Add Form Fields -->
                                <div class="form-group">
                                    <label>Book Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Author</label>
                                    <input type="text" name="author_name" class="form-control" required>
                                </div>
                               
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="number" name="quantity" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->cat_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Author Image</label>
                                    <input type="file" name="author_img" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Book Image</label>
                                    <input type="file" name="book_img" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@include('admin.footer')
</body>
</html>
