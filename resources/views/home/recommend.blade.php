<!DOCTYPE html>
<html lang="en">

<head>
    @include('home.css')
    <style type="text/css">
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .book-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 50px auto;
            max-width: 90%;
        }
        .book-card {
            background-color: #1f1f1f;
            padding: 15px;
            border-radius: 8px;
            width: 250px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }
        .book-card img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .book-card p {
            font-size: 14px;
            color: #bbb;
        }
    </style>
</head>

<body>
    @include('home.header')

    <div class="container">
        <h2 class="text-center">Recommended for You</h2>
        <div class="book-container">
            @foreach($recommendedBooks as $book)
            <div class="book-card">
                <img src="{{ asset('storage/' . $book->book_img) }}" alt="Book Image">
                <h3>{{ $book->title }}</h3>
                <p>Author: {{ $book->author_name }}</p>
            </div>
            @endforeach
        </div>
    </div>

    @include('home.footer')
</body>

</html>
