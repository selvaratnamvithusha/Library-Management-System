<!DOCTYPE html>
<html lang="en">

<head>
  @include('home.css')

  <style type="text/css">
    body {
      background-color: #121212; /* Dark background */
      color: #ffffff; /* White text for contrast */
    }

    .table_deg {
      width: 80%;
      margin: 100px auto;
      border-collapse: collapse;
      text-align: center;
      background-color: #1f1f1f; /* Dark table background */
      border: 1px solid #333; /* Subtle border for table */
    }

    th {
      background-color: #333; /* Darker header background */
      color: #ffffff; /* White text */
      font-size: 16px;
      padding: 12px;
    }

    td {
      background-color: #1a1a1a; /* Slightly lighter than the table */
      color: #ffffff;
      padding: 12px;
      border: 1px solid #333; /* Border matches the header */
    }

    .book_img {
      height: 100px;
      width: auto;
      margin: auto;
      display: block;
      border: 1px solid #555; /* Subtle border for images */
      border-radius: 4px;
    }

    .btn-warning {
      color: #000; /* Dark text */
      background-color: #ffc107; /* Golden button */
      border: none;
      padding: 5px 10px;
      font-size: 14px;
      border-radius: 4px;
      text-decoration: none;
    }

    .btn-warning:hover {
      background-color: #e0a800;
    }

    .not-allowed {
      color: #ff6b6b; /* Red for emphasis */
      font-weight: bold;
      font-size: 14px;
    }

    .alert-success {
      text-align: center;
      font-size: 16px;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid transparent;
      border-radius: 4px;
      background-color: #28a745; /* Green success background */
      color: #ffffff;
    }
  </style>

</head>

<body>

  @include('home.header')

  <div class="currently-market">
    <div class="container">
      <div class="row">

        @if (session()->has('message'))
        <div class="alert alert-success">
          {{ session()->get('message') }}
          <button type="button" class="close" aria-hidden="true" data-bs-dismiss="alert">X</button>
        </div>
        @endif

        <table class="table_deg">
          <tr>
            <th>Book Name</th>
            <th>Book Author</th>
            <th>Book Status</th>
            <th>Image</th>
            <th>Action</th>
          </tr>

          @foreach($data as $book)
          <tr>
            <td>{{ $book->book->title }}</td>
            <td>{{ $book->book->author_name }}</td>
            <td>{{ $book->status }}</td>
            <td>
              <img class="book_img" src="{{ asset('storage/' . $book->book->book_img) }}" alt="Book Image">
            </td>
            <td>
              @if($book->status == 'Applied')
              <a href="{{ url('cancel_req', $book->id) }}" class="btn btn-warning">Cancel</a>
              @else
              <p class="not-allowed">Not Allowed</p>
              @endif
            </td>
          </tr>
          @endforeach
        </table>

      </div>
    </div>
  </div>

  @include('home.footer')

</body>

</html>
